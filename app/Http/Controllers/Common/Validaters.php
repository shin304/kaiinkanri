<?PHP

namespace App\Http\Controllers\Common;

class Validaters {
    private static $_mailAddressRegix = '/^[a-zA-Z0-9_\.\-]+?@[A-Za-z0-9_\.\-]+$/';
    private static $_telNoRegix = '/^\d{2,4}-?\d{2,4}-?\d{4}$/';
    private static $_urlRegix = '/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/';
    private static $_faxNoRegix = '/^\d{2,4}-?\d{2,4}-?\d{4}$/';
    private static $_zipCodeRegix = '/^\d{3}-?\d{4}$/';
    private static $_manYenRegix = '/^\d+\.?\d{0,4}$/';
    private static $_taxRegix = '/^\d{1}\.\d{1,4}$/';
    private static $_alphaNumberRegix = '/^[A-Za-z0-9]+$/';
    private static $_alphaNumnerSimbolRegix = '/^[a-zA-Z0-9-\/:-@\[-\`\{-\~]+$/';
    private static $_hankakumojiRegix = '^[ｦｱ-ﾝﾞﾟ0-9A-Z\(\)\-\ ]+$';
    private static $_zenkakukatakanaRegix = '^[ア-ン゛゜ァ-ォャ-ョー「」、\　\ ]+$';
    const IS_DIGIT = 'isDigit';
    const _IS_DIGIT = '_isDigit';
    const IS_NUMERIC = 'isNumeric';
    const _IS_NUMERIC = '_isNumeric';
    const MAIL_ADDRESS = 'mailAddress';
    const _MAIL_ADDRESS = '_mailAddress';
    const TEL_NO = 'telNo';
    const _TEL_NO = '_telNo';
    const URL = 'url';
    const _URL = '_url';
    const BETWEEN_LENGTH = 'betweenLength';
    const _BETWEEN_LENGTH = '_betweenLength';
    const BETWEEN_VALUE = 'betweenValue';
    const _BETWEEN_VALUE = '_betweenValue';
    const REGEX = 'regex';
    const _REGEX = '_regex';
    const EQUALS = 'equals';
    const _EQUALS = '_equals';
    const NOT_EQUALS = 'notEquals';
    const _NOT_EQUALS = '_notEquals';
    const NOT_EMPTY = 'notEmpty';
    const _NOT_EMPTY = '_notEmpty';
    const OVER_LENGTH = 'overLength';
    const _OVER_LENGTH = '_overLength';
    const OVER_VALUE = 'overValue';
    const _OVER_VALUE = '_overValue';
    const UNDER_LENGTH = 'underLength';
    const _UNDER_LENGTH = '_underLength';
    const UNDER_VALUE = 'underValue';
    const _UNDER_VALUE = '_underValue';
    const UNDER_VALUE_2 = 'underValue2';
    const _UNDER_VALUE_2 = '_underValue2';
    const OBJECT = 'object';
    const _OBJECT = '_object';
    const FAX_NO = 'faxNo';
    const _FAX_NO = '_faxNo';
    const ZIP_CODE = 'zipCode';
    const _ZIP_CODE = '_zipCode';
    const MAN_YEN = 'manYen';
    const _MAN_YEN = '_manYen';
    const TAX = 'tax';
    const _TAX = '_tax';
    const IS_DATE = 'isDate';
    const _IS_DATE = '_isDate';
    const EQUALS_LENGTH = 'equalsLength';
    const _EQUALS_LENGTH = '_equalsLength';
    const IS_TIME = 'isTime';
    const _IS_TIME = '_isTime';
    const IS_TIME24 = 'isTime24';
    const _IS_TIME24 = '_isTime24';
    const IS_ALNUM = 'isAlNum';
    const _IS_ALNUM = '_isAlNum';
    const IS_ALNUMSIM = 'isAlNumSim';
    const _IS_ALNUMSIM = '_isAlNumSim';
    const IS_HANKAKU = 'isHankaku';
    const IS_ZENKAKUKANA = 'isZenkakukana';
    /**
     * 入力チェック
     *
     * @param string $method            
     * @param unknown_type $val            
     * @param array $params            
     * @return bool
     */
    public static function isValid($method, $val, $params = array()) {
        if (substr ( $method, 0, 1 ) == '_') {
            if (! strlen ( $val ) || (is_array ( $val ) && empty ( $val ))) {
                return true;
            }
            $method = substr ( $method, 1 );
        }
        return self::$method ( $val, $params );
    }
    
    /**
     * 整数チェック
     */
    private static function isDigit($val) {
        return ctype_digit ( strval ( $val ) );
    }
    
    /**
     * 数値チェック
     */
    private static function isNumeric($val) {
        return is_numeric ( $val );
    }
    
    /**
     * メールアドレスチェック
     */
    public static function mailAddress($val) {
        return self::regex ( $val, array (
                self::$_mailAddressRegix 
        ) );
    }
    
    /**
     * 電話番号チェック
     */
    private static function telNo($val) {
        return self::regex ( $val, array (
                self::$_telNoRegix 
        ) );
    }
    
    /**
     * ＦＡＸ番号チェック
     */
    private static function faxNo($val) {
        return self::regex ( $val, array (
                self::$_faxNoRegix 
        ) );
    }
    
    /**
     * 郵便番号チェック
     */
    private static function zipCode($val) {
        return self::regex ( $val, array (
                self::$_zipCodeRegix 
        ) );
    }
    
    /**
     * 万円チェック
     */
    private static function manYen($val) {
        return self::regex ( $val, array (
                self::$_manYenRegix 
        ) );
    }
    
    /**
     * 税率チェック
     */
    private static function tax($val) {
        return self::regex ( $val, array (
                self::$_taxRegix 
        ) );
    }
    
    /**
     * ＵＲＬチェック
     */
    private static function url($val) {
        return self::regex ( $val, array (
                self::$_urlRegix 
        ) );
    }
    
    /**
     * 文字列長範囲チェック
     */
    private static function betweenLength($val, $params = array()) {
        if (! is_array ( $params ) || count ( $params ) < 2) {
            return true;
        }
        return self::underLength ( $val, array (
                $params [0] 
        ) ) && self::overLength ( $val, array (
                $params [1] 
        ) );
    }
    
    /**
     * 値範囲チェック
     */
    private static function betweenValue($val, $params = array()) {
        if (! is_array ( $params ) || count ( $params ) < 2) {
            return true;
        }
        return self::underValue ( $val, array (
                $params [0] 
        ) ) && self::overValue ( $val, array (
                $params [1] 
        ) );
    }
    
    /**
     * 正規表現チェック
     */
    private static function regex($val, $params = array()) {
        if (! is_array ( $params ) || count ( $params ) < 1) {
            return true;
        }
        return preg_match ( $params [0], $val );
    }
    
    /**
     * 完全一致チェック
     */
    private static function equals($val, $params = array()) {
        if (! is_array ( $params ) || count ( $params ) < 1) {
            return true;
        }
        if (is_numeric ( $params [0] ) && is_numeric ( $val )) {
            return floatval ( $val ) === floatval ( $params [0] );
        }
        return $val === $params [0];
    }
    
    /**
     * 非完全一致チェック
     */
    private static function notEquals($val, $params = array()) {
        if (! is_array ( $params ) || count ( $params ) < 1) {
            return true;
        }
        if (is_numeric ( $params [0] ) && is_numeric ( $val )) {
            return floatval ( $val ) !== floatval ( $params [0] );
        }
        return $val !== $params [0];
    }
    
    /**
     * 空チェック
     */
    private static function notEmpty($val) {
        if (is_array ( $val )) {
            return ! empty ( $val );
        }
        return self::underLength ( $val, array (
                1 
        ) );
    }
    
    /**
     * 文字列長オーバーチェック
     */
    public static function overLength($val, $params = array()) {
        if (! is_array ( $params ) || count ( $params ) < 1) {
            return true;
        }
        $len = mb_strlen ( strval ( $val ) );
        return $len <= intval ( $params [0] );
    }
    
    /**
     * 文字列長アンダーチェック
     */
    private static function underLength($val, $params = array()) {
        if (! is_array ( $params ) || count ( $params ) < 1) {
            return true;
        }
        $len = mb_strlen ( strval ( $val ) );
        return $len >= intval ( $params [0] );
    }
    
    /**
     * 値オーバーチェック
     */
    private static function overValue($val, $params = array()) {
        if (! is_array ( $params ) || count ( $params ) < 1) {
            return true;
        }
        if (is_numeric ( $params [0] ) && is_numeric ( $val )) {
            return floatval ( $val ) <= floatval ( $params [0] );
        }
        return $val <= $params [0];
    }
    
    /**
     * 値アンダーチェック
     */
    private static function underValue($val, $params = array()) {
        if (! is_array ( $params ) || count ( $params ) < 1) {
            return true;
        }
        if (is_numeric ( $params [0] ) && is_numeric ( $val )) {
            return floatval ( $val ) >= floatval ( $params [0] );
        }
        return $val >= $params [0];
    }
    
    /**
     * 値アンダーチェック2
     */
    private static function underValue2($val, $params = array()) {
        if (! is_array ( $params ) || count ( $params ) < 1) {
            return true;
        }
        if (is_numeric ( $params [0] ) && is_numeric ( $val )) {
            return floatval ( $val ) > floatval ( $params [0] );
        }
        return $val > $params [0];
    }
    
    /**
     * オブジェクトチェック
     */
    private static function object($val, $params = array()) {
        if (! is_array ( $params ) || count ( $params ) < 3) {
            return true;
        }
        $object = $params [0];
        $method = $params [1];
        $para = $params [2];
        if (! is_object ( $object ) || ! is_string ( $method ) || ! is_array ( $para )) {
            return true;
        }
        return $object->$method ( $para );
    }
    
    /**
     * 日付チェック
     */
    public static function isDate($val, $params = array()) {
        $len = strlen ( $val );
        $str = array ();
        if ($len == 8) {
            $reg = "/^\d{8}?$/";
            $str [0] = substr ( $val, 0, 4 );
            $str [1] = substr ( $val, 4, 2 );
            $str [2] = substr ( $val, 6, 2 );
        } elseif ($len == 10) {
            $reg = "/^\d{4}?[\/\-]\d{2}?[\/\-]\d{2}?/";
            $str [0] = substr ( $val, 0, 4 );
            $str [1] = substr ( $val, 5, 2 );
            $str [2] = substr ( $val, 8, 2 );
        } else {
            return false;
        }
        if (! preg_match ( $reg, $val )) {
            return false;
        }
        if (! checkdate ( $str [1], $str [2], $str [0] )) {
            return false;
        }
        return true;
    }
    public static function isTime($val, $params = array()) {
        $len = strlen ( $val );
        $str = array ();
        $reg = "/^(2[0-3]|[01]?[0-9]):[0-5][0-9]" . (isset ( $params ['sec'] ) ? ":[0-5][0-9]$/" : "$/");
        if (! preg_match ( $reg, $val )) {
            return false;
        }
        return true;
    }
    public static function isTime24($val, $params = array()) {
        $len = strlen ( $val );
        $str = array ();
        $reg = "/^((2[0-3]|[01]?[0-9]):([0-5][0-9])|24:00)$/";
        if (! preg_match ( $reg, $val )) {
            return false;
        }
        return true;
    }
    public static function isAlNum($val, $params = array()) {
        return self::regex ( $val, array (
                self::$_alphaNumberRegix 
        ) );
    }
    public static function isAlNumSim($val, $params = array()) {
        return self::regex ( $val, array (
                self::$_alphaNumnerSimbolRegix 
        ) );
    }
    
    /**
     * 文字列長チェック
     */
    private static function equalsLength($val, $params = array()) {
        if (! is_array ( $params ) || count ( $params ) < 1) {
            return true;
        }
        $len = mb_strlen ( strval ( $val ) );
        return $len == intval ( $params [0] );
    }
    
    /**
     * 半角英数カナ文字チェック
     */
    public static function isHankaku($val, $params = array()) {
        // return self::regex( $val, array( self::$_hankakumojiRegix) );
        return mb_ereg ( self::$_hankakumojiRegix, $val );
    }
    
    /**
     * 全角カナ文字チェック
     */
    public static function isZenkakukana($val, $params = array()) {
        return mb_ereg ( self::$_zenkakukatakanaRegix, $val );
    }
}
