<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public static function getReportAbuseOptionList(){
        return [
           1 => ['name' => 'Nudity','id' => 1],
           2 => ['name' => 'Promotes hate, violence or illegal/offensive activities', 'id' => 2],
           3 => ['name' => 'Spam, malware or "phishing" (fake login)', 'id' => 3],
           4 => ['name' => 'Private and confidential information', 'id' => 4],
           5 => ['name' => 'Copyright infringement', 'id' => 5],
        ];
    }

    public static function array_list_by_element_index( $staticfunction, $index = 'en' ) {
        // for religions
        // self::array_list_by_element_key('religions', 'en');
        $out_array = [];
        foreach ( self::$staticfunction() as $key => $array ) {
            $out_array[$key] = $array[$index];
        }
        return $out_array;
    }

    public static function get_data_by_index( $staticfunction, $index, $lan = 'en', $extra_title = null ) {
        $found = in_array( $index, array_keys( self::$staticfunction() ) );
        if ( !$found ) {
            return null;
        }
        $title_lan = $extra_title . '_' . $lan;
        return $extra_title != null ? self::$staticfunction()[$index][$title_lan] : self::$staticfunction()[$index][$lan];
    }

    public static function get_other_data_by_index( $staticfunction, $index, $lan, $extra_titles = [] ) {
        $data = [];
        if ( empty( $extra_titles ) ) {
            return $data;
        }
        foreach ( $extra_titles as $key => $extra_title ) {
            $title_value = self::get_data_by_index( $staticfunction, $index, $lan, $extra_title );

            $data[$key] = $title_value;
        }
        return $data;
    }
    public static function get_array_by_a_specific_index($staticfunction, $index, $index_value){
        $out_array = [];
        $i=0;
        foreach ( self::$staticfunction() as $key => $array ) {

            if($array[$index]==$index_value)
            $out_array[$i++] = $array;
        }
        return $out_array;

    }

    public static function check_keys_exists( $staticfunctions = [], $indices = [] ) {
        $fields = [];
        if ( empty( $staticfunctions ) || empty( $indices ) ) {
            return false;
        }

        if ( is_array( $staticfunctions ) ) {

            foreach ( $staticfunctions as $key => $fnc ) {

                $get_field = self::check_key_exists( $fnc, $indices[$key] );

                if ( $get_field != "" ) {
                    $fields[$get_field] = $get_field;
                }

            }
        }

        return $fields;
    }

    public static function check_key_exists( $staticfunction, $index ) {
        $key_exists = array_key_exists( request()->$index, self::$staticfunction() );
        $field      = "";

        if ( !$key_exists ) {
            $field = $index;
        }

        return $field;
    }

    public static function get_array_that_return_only_key( $staticfunction, $index = 'key' ) {

        $out_array = [];
        foreach ( self::$staticfunction() as $key => $array ) {
            array_push( $out_array, $array[$index] );
        }
        return $out_array;
    }

    // Array Search With Specific Cloumn Value
    public static function searchWithColumn( $staticfunction, $coloumn, $value ) {
        $data = self::$staticfunction();

        $key = array_search( $value, array_column( $data, $coloumn ) );
        return $data[$key + 1];
    }

    public static function get_array_without_index($array_with_index){
        $remove_indices = [];
        foreach($array_with_index as $array){
            $remove_indices[] = $array;
        }
        return $remove_indices;
    }
}
