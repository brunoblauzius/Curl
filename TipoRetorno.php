<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//header("Content-type: text/xml");
/**
 * Description of TipoRetorno
 *
 * @author bruno.blauzius
 */
class TipoRetorno {
    
    private static $dados = array();
    
    /**
     * @version 1.0
     * @todo metodo construtor, é encarregado de verificar se é um objeto ou array e atribuir o valor ao atributo 
     * @param type $dados
     */
    public function __construct( $dados ) {
        self::$dados = is_array($dados)? (array) ($dados) : $dados;
    }
    
	public static function setDados( $dados ) {
        self::$dados = is_array($dados)? (array) ($dados) : $dados;
    }
	
	
	public static function retorno( $dados, $saida ){
		self::setDados( $dados );
		if( strtolower($saida) == "json" ){
			return self::getJson();
		} else if( strtolower($saida) == "xml" ) {
			return self::getXml('wsbci', array(''));
		}
	}
	
    /**
     * @version 1.0
     * @todo metodo que retorna os dados convertidos em xml
     * @return string Xml
     */
    public static function getXml( $node, array $attrbute = null ){
        header("Content-type: text/xml; charset=utf-8");
        $xml = new SimpleXMLElement( "<?xml version='1.0' ?>\n <{$node} ".join(' ', $attrbute )."></{$node}>" );
        self::addNodes(self::$dados, $xml);
        return $xml->asXML();
    }
    
    /**
     * @version 1.0
     * @todo metodo recursivo que adiciona nos ao meu xml, este metodo depende do metodo GetXml
     * @param array $lista
     * @param SimpleXMLElement $objetoXml
     */
    public static function addNodes( array $lista , SimpleXMLElement &$objetoXml ){
        
        if(is_array($lista) && !empty($lista)){
            
            foreach ( $lista as $key => $value ) {
                
                $key = is_numeric($key)? "registro" : $key;
                
                if( !is_array($value)  ) {
                    $objetoXml->addChild($key, $value);
                } else if( is_array($value) && !empty( $value ) ) {
                    $newObjeto = $objetoXml->addChild( $key  );
                    self::addNodes( $value , $newObjeto );
                }
                
            }
        }
    }
    
    
    /**
     * @version 1.0
     * @todo metodo que me retorna meus dados convertidos em json
     * @return string json
     */
    public static function getJson(){
        return json_encode(self::$dados);
    }
    
        
}





