<?php
namespace lib;

/**
 * Description of CurlS
 *
 */
class CurlS{
    
    public static $baseUrl = null;
    
    /**
     * 
     * @param curl_init $ch
     * @param array $parametros
     */
    private static function setPUTOptions( $ch, $parametros ){
        curl_setopt_array($ch, array(
                    CURLOPT_URL => self::$baseUrl,
                    CURLOPT_POSTFIELDS => json_encode($parametros)
                ));
    }
    
    /**
     * 
     * @param curl_init $ch
     * @param array $parametros
     */
    private static function setGETOptions( $ch, $parametros ){
        $param = array(CURLOPT_URL => self::$baseUrl );
        if(!empty($parametros)){
            $param = array(CURLOPT_URL => self::$baseUrl . '?' . http_build_query($parametros) );
        } 
        curl_setopt_array($ch, $param);
    }
    
    /**
     * 
     * @param curl_init $ch
     * @param array $parametros
     */
    private static function setPOSTOptions( $ch, $parametros ){
        curl_setopt_array($ch, array(
                    CURLOPT_URL => self::$baseUrl,
                    CURLOPT_POST => TRUE,
                    CURLOPT_POSTFIELDS => http_build_query($parametros)
                ));
    }
    
    /**
     * 
     * @param curl_init $ch
     * @param array $parametros
     */
    private static function setDELETEOptions( $ch, $parametros ){
        curl_setopt_array($ch,
                    array(CURLOPT_URL => self::$baseUrl . '?' . http_build_query($parametros) )
                );
    }
    
    /**
     * 
     * @param array $parametros
     * @param string $typeRequest
     * @param string $url
     * @param string $requestMethod
     * @param array $options
     * @return string
     * @throws \lib\Exception
     * @throws \Exception
     */
    public static function send( array $parametros , $url = NULL, $requestMethod = 'GET', $options = array() ){
        try{
            if( !is_null($url) ){
                self::$baseUrl = $url;
            }
            
            /**
             * init curl
             */
            $ch = curl_init();
            /**
             * options default to send.
             */
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CONNECTTIMEOUT => 0,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CUSTOMREQUEST => strtoupper($requestMethod),
            ));
           
            

            //curl_setopt($ch, CURLOPT_HTTPHEADER, TRUE);
            //verifico o tipo de envio
            if(strtoupper($requestMethod) === 'POST' ){
                self::setPOSTOptions($ch, $parametros);
            } 
            else if( strtoupper($requestMethod) === 'GET' ) {
                self::setGETOptions($ch, $parametros);
            } 
            else if( strtoupper($requestMethod) === 'DELETE' ){
                self::setDELETEOptions($ch, $parametros);
            } 
            else if( strtoupper($requestMethod) === 'PUT' ){
                self::setPUTOptions($ch, $parametros);
            }
            
            /**
             * optional params
             */
            if( !empty($options) ){
                curl_setopt_array($ch,$options);
            }
            
            /**
             * exec curl
             */
            $dados = curl_exec($ch);
            /**
             * verify error
             */
            $nErro = curl_errno($ch);
            if( $nErro  ){
                throw new \Exception( self::ErrorCurl($nErro) );
            }
            return $dados;
            
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    
    public static function ErrorCurl( $Nerro ){
        switch ($Nerro) {
            case 28:
                return ('O tempo limite da operação foi atingido!');
                break;

            default:
                return 'Falha de comunicação!';
                break;
        }        
    }
    
    
    
}
