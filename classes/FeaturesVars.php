<?php

/**
 * Created by PhpStorm.
 * User: egors
 * Date: 20.04.2018
 * Time: 11:33
 */
class FeaturesVars
{
    private static $instance;

    private static $fMarkaId;
    private static $fModelId;
    private static $fModifId;
    public static $fUnivId;

    private static $fMarkaName;
    private static $fModelName;
    private static $fModifName;
    public static $fUnivName;

    private function __construct(){
        self::setMarka();
        self::setModel();
        self::setModif();
        self::setUniv();
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public static function setMarka() {
        $array = DbQueries::mfFeature(1);
        self::$fMarkaId = $array[0]['id_feature'];
        self::$fMarkaName = Tools::str2url($array[0]['name']);
    }
    public static function setModel() {
        $array = DbQueries::mfFeature(2);
        self::$fModelId = $array[0]['id_feature'];
        self::$fModelName = Tools::str2url($array[0]['name']);
    }
    public static function setModif() {
        $array = DbQueries::mfFeature(3);
        self::$fModifId = $array[0]['id_feature'];
        self::$fModifName = Tools::str2url($array[0]['name']);
    }
    public static function setUniv() {
        $array = DbQueries::mfFeature(4);
        self::$fUnivId = $array[0]['id_feature'];
        self::$fUnivName = Tools::str2url($array[0]['name']);
    }

    /**
     * @return mixed
     */
    public static function getFUnivId()
    {
        return self::$fUnivId;
    }

    /**
     * @return mixed
     */
    public static function getFUnivName()
    {
        return self::$fUnivName;
    }

    /**
     * @return mixed
     */
    public static function getFMarkaId()
    {
        return self::$fMarkaId;
    }

    /**
     * @return mixed
     */
    public static function getFModelId()
    {
        return self::$fModelId;
    }

    /**
     * @return mixed
     */
    public static function getFModifId()
    {
        return self::$fModifId;
    }

    /**
     * @return mixed
     */
    public static function getFMarkaName()
    {
        return self::$fMarkaName;
    }

    /**
     * @return mixed
     */
    public static function getFModelName()
    {
        return self::$fModelName;
    }

    /**
     * @return mixed
     */
    public static function getFModifName()
    {
        return self::$fModifName;
    }

}