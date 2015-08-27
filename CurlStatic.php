<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CurlStatic
 *
 * @author bruno.blauzius
 */
class CurlStatic {
    
    public static $baseUrl = null;
    
    /**
     * 
     * @param array $parametros
     * @param type $typeRequest
     * @param type $url
     * @throws Exception
     * 
     * @example CurlStatic::send( array( 'chave' => 'SHA34TRV12BASJSHD8RI75OSPE823AAS', 'placa' => 'ELC7167'), 'json', $url, 'GET' );
     * 
     */
    public static function send( array $parametros , $typeRequest = 'XML' , $url = NULL, $requestMethod = 'GET' ){
        try{
            if( !is_null($url) ){
                self::$baseUrl = $url;
            }
            
            $ch = curl_init();
            
            $http = http_build_query( $parametros );
            
            
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 0);
            //tempo maximo de requizição
            curl_setopt($ch, CURLOPT_TIMEOUT , 6);
            //verificação http segura falsa
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, true);
            //curl_setopt($ch, CURLOPT_HEADER, true);
            
            
            //curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
            //curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
            
            //verifico o tipo de envio
            if( $requestMethod === 'POST' || $requestMethod === 'PUT' ){
                curl_setopt($ch, CURLOPT_URL, self::$baseUrl );
                curl_setopt($ch, CURLOPT_POST , TRUE );
                curl_setopt($ch, CURLOPT_POSTFIELDS ,  $http );
            } else if( $requestMethod === 'GET' ) {
                if(!empty($http)){
                    curl_setopt($ch, CURLOPT_URL, self::$baseUrl . '?' . $http );
                } else {
                    curl_setopt($ch, CURLOPT_URL, self::$baseUrl );
                }
                
            }
            
            //faço o retorno dos dados obtidos
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            //faço um redirecionamento se preciso
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            
            $dados = curl_exec($ch);
            
            //verifico se houve erro
             $nErro = curl_errno($ch);
             curl_error($ch);
            if( $nErro  ){
                throw new Exception( self::tartarErroCurl($nErro), $nErro );
            }
            
            return $dados;
            
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    
    public static function tartarErroCurl( $Nerro ){
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

//echo CurlStatic::send(array(), 'json', 'https://www.sistemasx.com.br/ws/cmcpj/v1/sinesp.php?placa=knn6151&chave=5AB20BCDC6D4132F4A4A645160F2F521', 'GET');