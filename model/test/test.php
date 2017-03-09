<?php
namespace model\test;
class Test {

    public function runAllTests() {

        $this->reload( 2 );

        $this->textRoute();

        $files = rsearch( __MODEL_DIR__, '_test.php' );



        foreach ( $files as $file ) {


            $file = str_replace(".php", '', $file);
            $arr = array_reverse( preg_split( "/[\\\\\/]/", $file));

            $path = "model\\$arr[1]\\$arr[0]";



            $obj = new $path();

            if ( method_exists( $obj, 'run' ) ) {
                $obj->run();
            }
        }



    }

    public function reload( $n ) {
        $seconds = $n * 1000;
        echo "
            <script>
                setTimeout( function() {
                    location.reload( true );
                }, $seconds );
            </script>
        ";
    }

    private function textRoute() {
        $re = $this->route("version");
        test ( is_success( $re ), "Route Access OK. " . get_error_string($re));

    }


    /**
     *
     * /**
     *
     */
    public function route( $route_name, $params = [] ) {

        $_REQUEST = $params;

        ob_start();
        $route = get_route( $route_name );
        if ( empty($route) ) return ['code' => ERROR_ROUTE_NOT_EXIST, 'message' => 'No route exists' ];
        run_route( $route );
        $data = ob_get_clean();
        if ( empty($data) ) return [ 'code' => ERROR_NO_RESPONSE, 'message' => 'No data received' ];
        $res = json_decode($data, true);

        return $res;
    }



    /**
     * this returns random string.
     * @param int $length
     * @return string
     */

    function randomString($length = 1) {
        $str = "";
        $characters = array_merge(range('A','Z'), range('a','z') );
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }


    public function createUser( $record ) {

        user( $record['id'] )->delete();

        $re = $this->route( "register", $record );
        return is_success($re) ? $re['data']['session_id'] : null;

    }

}