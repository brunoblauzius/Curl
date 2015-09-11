<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utils
 *
 */
class Utils {
    
    const VALORES_DO_PARAMETRO_VAZIO = 'parametro vazio para a data';
    
    const DATA_FORMATO_INVALIDO      = 'data de formato inválido';
    
    public $meses = array( 1 => 'Janeiro', 'Fevereiro', 'Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro' );
    
    public static $replace = array('\n', '\t');

    
    private function nomeMes( $mes = null ){
        if( in_array( $mes, array_keys($this->meses) ) ){
            return  $this->meses[$mes];
        } 
    } 
    
    /**
     * 
     * @param string $data
     * @return string
     */
    public function created( $data = NULL ) {
        if(is_null($data)){
            $data = date('d/m/Y');
        }
        $data = str_replace('T', ' ', $data );
        $data = explode(' ', $data );
        $explode = explode('-',  array_shift($data));
        $mes     = $this->nomeMes( (int) $explode[1] );
        $explode = array_reverse($explode);
        return array_shift($explode). ' de ' . $mes . ', ' .$explode[1] .' as ' . $data[0] ;
    }

    
    /**
     * 
     * @param string $dataInicio
     * @param string $dataFim
     * @return string
     */
    public function atualOrDate( $dataInicio = NULL, $dataFim = NULL){
        (string) $resultado = null;
        if( empty($dataFim) ){
            return 'Atualmente';
        } else {
            $dataInicio = explode('/', $dataInicio);
            $dataFim = explode('/', $dataFim);
            $mesDataFim = $this->nomeMes( (int) $dataFim[0] );
            $mesDataInicio = $this->nomeMes( (int) $dataInicio[0] );
            $resultado = $mesDataInicio . ' de ' . $dataInicio[1] . ' / ' . $mesDataFim .' de ' . $dataFim[1];
            return $resultado;
        }
    }
    
	/**
     * @todo metodo que reverte a data da view para o banco neste formato 0000-00-00T00:00:00
     * @param string $data
     * @return string
     */
    public static function revertDate( $data ){
        if( !is_null($data)) {
            if( stripos($data, 'T') === FALSE ) {
                $dataExplode = null;
                $dataExplode = explode(' ', trim($data));
                if(is_array($dataExplode)) {
                        $data        = join('-', array_reverse(explode('/', $dataExplode[0])));
                        if( strlen($dataExplode[1]) == 5 ) {
                            $dataExplode[1] = $dataExplode[1] . ':00';
                        }
                        return $data .'T'. $dataExplode[1];
                } else {
                        $data        = join('-', array_reverse(explode('/', $data)));
                        return $data;
                }
            }
            return $data;
        }
    }
    
    /**
     * @todo metodo que reverte a data do banco para a view neste formato 00/00/0000 00:00
     * @param string $data
     * @return string
     */
    public static function getDate( $data ) {
        if( !is_null($data)) {
                $dataExplode = null;
                $dataExplode = explode('T', $data);
                if( is_array($dataExplode)) {
                        $data        = join('/', array_reverse(explode('-', $dataExplode[0])));
                        if(strlen($dataExplode[1]) == 5 ){
                            $dataExplode[1] = $dataExplode[1] . ':00';
                        }
                        return $data .' '. $dataExplode[1];
                } else {
                        $dataExplode = explode(' ', $data);
                        $hora = $dataExplode[1];
                        return join('/', array_reverse(explode('-', $dataExplode[0]))) . ' ' . $hora ;
                        
                }
        }
        return null;
    }
	
	
    /**
     * @author Bruno Blauzius
     * @todo metodo para conversão de datas view/data base
     * @param string $data
     * @return styring
     * @throws Exception
     * 
     */
    public static function convertData( $data = null ){
		if( !empty($data) ){				
			$exData = explode(' ', $data);
			
			if( preg_match( '/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $exData[0] ) ){
			   $data = explode('/', $exData[0]);
			   $data = implode( '-',array_reverse($data) ). ' ' . $exData[1];
			} else if( preg_match( '/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/', $exData[0] ) ){
			   $data = explode('-', $exData[0]);
			   $data = implode( '/',array_reverse($data) ) . ' ' . $exData[1] ;
			} else {
			   $data = (  'data no formato inválido'  );
			}
		}
		return $data;
    }
    
    /**
     * @author Bruno Blauzius
     * @todo metodo para conversão de datas view/data base
     * @param string $data
     * @return styring
     * @throws Exception
     * 
     */
    public static function convertDataSemHora( $data = null ){
		if( !empty($data) ){
			if( preg_match( '/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $data ) ){
			   $data = explode('/', $data);
			   $data = implode( '-',array_reverse($data) );
			} else if( preg_match( '/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/', $data ) ){
			   $data = explode('-', $data);
			   $data = implode( '/',array_reverse($data) );
			} else {
			   $data = (  'data no formato inválido'  );
			}
		}
		return $data;
    }
    
    
    /**
     * @retiro todos os numeros da minha string alfanumerica
     * @param string $string
     * @return int
     */
    public static function returnNumeric( $string = null ) {
        preg_match_all('/[0-9]/', $string, $matches );
        return join( array_shift( $matches ) );
    }
    
    
    /**
     * @todo metodo que falz a limpeza dos css de uma string
     * @param string $string
     * @return string
     */
    public static final  function sanitazeCss( $string = null ) {
        return preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $string);
    } 
    
    /**
     * @todo metodo que falz a limpeza dos java script de uma string
     * @param string $string
     * @return string
     */
    public static final  function sanitazeJavascript( $string = null ){
        return preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $string);
    }
    
    
    /**
     * @todo metodo que retorna uma string sem erros
     * @param string $string
     * @return strin
     */
    public static final function sanitazeString( $string = NULL ){
        $string = self::sanitazeJavascript( $string );
        $string = self::sanitazeCss( $string );
        $string = strip_tags( $string );
        $string = str_replace( self::$replace, '', $string);
        return trim( addslashes($string) );
    }
    
    /**
     * @todo metodo que faz um sanitaze Na lista inteira de array enviado pelo metodo request
     * @param array $oldArray
     * @return array
     */
    public static final function sanitazeArray( array $oldArray = null ){
        (array) $newArray = array();
        $model = key($oldArray);
        foreach ( $oldArray as $key => $array ) {
            foreach ( $array as $chave => $string ) {
                if( !is_array($string) ){
                    $newArray[$key][$chave] = self::sanitazeString($string);
                }
            }
        }
        return $newArray;
    }
    
    
    /**
     * @todo metodo desenvolvido para truncar a string para a view
     * @param string $string
     * @param int $limit
     * @return string
     */
    public function strTruncate( $string = null, $limit = 30 ){
        if(is_string($string)){
            if(strlen($string) <= $limit ){
                return $string;
            }
            return substr($string,0,strpos($string,' ',$limit)) ;
        }
    }
	
	public static function real( $valor ){
		if(!empty( $valor )){
			$valor = number_format( $valor , 2 ,',', '.');
                } else {
                    $valor = 0.0;
                }
		return $valor;
	}
    
	public static function comissao( $porcentagem, $valorTotal ){
            if( $porcentagem == 0 ){
                return $valorTotal;
            }
            return ( $porcentagem / 100 ) * $valorTotal;
	}
    
    
    /**
     * @todo metodo que valida o cpf solicitado
     * @param string $num
     * @return boolean
     */
    public static function validaCpf( $num ) {
        $num = preg_replace( "/[^0-9]/", "", ($num) );
        $num = substr( $num, -11 );
        
        if( preg_match( "/(\d)\\1{10}/", $num ) || "12345678909" == $num ) {
            return false;
        }
        return strlen( $num ) == 11
                && self::mod( $num, 9 )
                && self::mod( $num, 10 );
    }

    /**
     * @todo metodo que valida o cnpj solicitado
     * @param string $num
     * @return boolean
     */
    public static function validaCnpj( $num ) {
     
        $num = preg_replace( "/[^0-9]/", "", ($num) );
        $num = preg_replace( "/^\d?(\d{14})$/", "\\1", $num );
        
        return strlen( $num ) == 14
                && self::mod( $num, 12, true )
                && self::mod( $num, 13, true );
    }

    /**
     * @todo metodo que realiza o calculo do cnpj/cpf para a validação
     * @param type $num
     * @param type $dig
     * @param type $isCnpj
     * @return type
     */
    private static function mod( $num, $dig, $isCnpj = false ) {
        $sum = 0;
        for( $i = 0; $i < $dig; $i++ ) {
            $vl = $dig + 1 - $i;
            if( $isCnpj && $vl > 9 ) {
                $vl %= 8;
            }
            $sum += intval( $num[ $i ] ) * $vl;
        }
        $res = 11 - ( $sum % 11 );
        return ( $res > 9 ? 0 : $res ) == intval( $num[ $dig ] );
    }
    
    
    /**
     * @version 1.0
     * @todo metodo que retorna o valor com a pontuação do cpf ou do cnpj para o usuario
     * @param string $string valor do documento
     * @param string $tipo cpf/cnpj
     * @return string 
     */
    public static function formatarCPFCNPJ ($string, $tipo = ""){
    
        $string = self::returnNumeric($string);

        while(strlen($string) < 11){        
            $string = self::incluiZeroEsquerda($string);        
        }

        while(strlen($string) < 14 && strlen($string) > 11){
            $string = self::incluiZeroEsquerda($string);
        }

        if (!$tipo)
        {
            switch (strlen($string))
            {
                case 11:    $tipo = 'cpf';      break;
                case 14:    $tipo = 'cnpj';     break;
            }
        }
        switch ($tipo)
        {
            case 'cpf':
                $string = substr($string, 0, 3) . '.' . substr($string, 3, 3) . 
                    '.' . substr($string, 6, 3) . '-' . substr($string, 9, 2);
            break;
            case 'cnpj':
                $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) . 
                    '.' . substr($string, 5, 3) . '/' . 
                    substr($string, 8, 4) . '-' . substr($string, 12, 2);
            break;
            case 'rg':
                $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) . 
                    '.' . substr($string, 5, 3);
            break;
        }
        return $string;
    }
    
    /**
     * @version 1.0
     * @todo metodo que retorna o valor com a pontuação do CEP para o usuario
     * @param string $string valor do CEP
     * @return string 
     */
    public static function formatarCEP ($string)
    {
        $string = ereg_replace("[^0-9]", "", $string);

        $string = substr($string, 0, 5) . '-' . substr($string, 5, 3);
           
        return $string;
    }
    
    /**
     * @version 1.0
     * @todo metodo que retorna o valor com a pontuação do telefone para o usuario
     * @param string $string valor do telefone
     * @return string 
     */
    public static function formatarTelefone ($string)
    {
        $string = ereg_replace("[^0-9]", "", $string);
        
        if (strlen($string) == 10) 
            $string = '(' . substr($string, 0, 2) . ') ' . substr($string, 2, 4) . '-' . substr($string, 6);
        else
            $string = '(' . substr($string, 0, 2) . ') ' . substr($string, 2, 1) . ' ' . substr($string, 3, 4) . '-' . substr($string, 7);
        
        return $string;
    }

    
    /**
     * @version 1.0
     * @todo metodo que inclui um zero caso seja salvo na base com caractere a menos cpf/cnpj
     * @param string $valor cpf
     * @return string
     */
    public static function incluiZeroEsquerda($valor){    
        return '0'.$valor;
    }
	
    
    public static function adicionaMes( $int = 1, $date = null ){
        if(is_null($date)){
            return null;
        }
        return date('Y-m-d H:i:s', strtotime("+$int month",strtotime($date)));
    }
    
    public static function adicionaHora( $int = 1, $date = null ){
        if(is_null($date)){
            return null;
        }
        return date('Y-m-d H:i:s', strtotime("+$int hours",strtotime($date)));
    }
    
    
    public static function pre( $valor ){
        echo '<pre>';
            print_r($valor);
        echo '</pre>';
    }
    
}
