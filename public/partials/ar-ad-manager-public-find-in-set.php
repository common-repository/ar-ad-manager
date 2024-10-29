<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) exit;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Partials_Ar_Ad_Manager_Public_Find_In_Set
 */
class Partials_Ar_Ad_Manager_Public_Find_In_Set
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('posts_where', [$this, 'posts_where'], 10, 2);
    }

    /**
     * @param $where
     * @param $query
     * @return array|mixed|string|string[]|null
     */
    public function posts_where($where, $query)
    {
        global $wpdb;

        foreach ($query->meta_query->queries as $i => $mq) {
            if (isset($mq['compare']) && 'find_in_set' == strtolower($mq['compare'])) {
                $prefix = (0 == $i)
                    ? $wpdb->postmeta
                    : 'mt' . $i;

                $regex = sprintf(
                    "#\\([\n\r\\s]+(%s.meta_key = '%s') AND (%s.meta_value) = ('%s')[\n\r\\s]+\\)#m",
                    $prefix,
                    preg_quote($mq['key']),
                    $prefix,
                    preg_quote($mq['value'])
                );

                // Replace the compare '=' with compare 'find_in_set'.
                $where = preg_replace($regex, '($1 AND FIND_IN_SET($3,$2))', $where);
            }
        }

        return $where;
    }
}

new Partials_Ar_Ad_Manager_Public_Find_In_Set();