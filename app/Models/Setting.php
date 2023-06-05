<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public static function getReportAbuseOptionList(){
        return [
           1 => ['name' => 'Nudity','title'=>'Our policy on nudity','title_description'=> 'We don\'t allow the sharing or publishing of content depicting nudity, graphic sex acts, or other sexually explicit material. We also don\'t allow content that drives traffic to commercial pornography sites or that promotes pedophilia, incest, or bestiality.','id' => 1],
           2 => ['name' => 'Promotes hate, violence or illegal/offensive activities','title'=>'Our policy on hate, violence and illegal or offensive activities', 'title_description'=>'Users may not share or publish content that promotes hate or violence towards other groups based on race, ethnicity, religion, disability, gender, age, veteran status, or sexual orientation/gender identity. Please note that individuals are not considered a protected group.
           Users may not share or publish crude content or violent content that is shockingly graphic.
           We will also remove content that threatens, harasses or bullies other people or promotes dangerous and illegal activities.', 'id' => 2],
           3 => ['name' => 'Spam, malware or "phishing" (fake login)','title'=>'Our policy on spam, malware and "phishing" (fake login)','title_description'=>'We do not allow spamming or content that transmits viruses, causes pop-ups, attempts to install software with the user\'s consent, or otherwise impacts users with malicious code or scripts. Also, we do not allow phishing activity.', 'id' => 3],
           4 => ['name' => 'Private and confidential information','title'=>'Our policy on private and confidential information', 'title_description'=> 'We do not allow the posting of another person\'s personal and confidential account or identification information. For example, we do not allow the sharing or publishing of another person\'s credit card number or account passwords.', 'id' => 4],
           5 => ['name' => 'Copyright infringement','title'=>'Our policy on copyright infringement', 'title_description'=>'It is our policy to respond to clear notices of alleged copyright infringement. If you own the copyright to material in this document, please use this form to report an official Digital Millennium Copyright Act (DMCA) complaint:', 'id' => 5],
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
