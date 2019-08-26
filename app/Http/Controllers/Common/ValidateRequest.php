<?PHP

namespace App\Http\Controllers\Common;

class ValidateRequest {
    private $_rules;
    private $_errors;
    
    /**
     * コンストラクタ
     *
     * @param array $rules            
     */
    public function __construct($rules = array()) {
        $this->refreshRules ( $rules );
    }
    
    /**
     * チェックルールをリフレッシュする
     *
     * @param array $rules            
     */
    public function refreshRules($rules = array()) {
        $this->_rules = $rules;
        $this->_errors = array ();
    }
    
    /**
     * 配列に対応してみた
     */
    public function isValid($vals) {
        $this->_errors = $this->isValidArray ( $vals, $this->_rules );
        return ! empty ( $this->_errors );
    }
    private function isValidArray($vals, &$rules) {
        $errors = array ();
        foreach ( $vals as $key => $val ) {
            if (! isset ( $rules [$key] )) {
                continue;
            }
            if (is_array ( $val )) {
                $ret = $this->isValidArray ( $val, $rules [$key] );
                if (! empty ( $ret )) {
                    $errors [$key] = $ret;
                }
            } else {
                $rule = $rules [$key];
                foreach ( $rule as $method => $param ) {
                    if (! Validaters::isValid ( $method, $val, $param )) {
                        if (substr ( $method, 0, 1 ) == '_') {
                            $method = substr ( $method, 1 );
                        }
                        $errors [$key] [$method] = true;
                    }
                }
            }
        }
        return $errors;
    }
    
    /**
     * ルールから見るようにしました。
     *
     * @param unknown_type $vals            
     * @return unknown
     */
    public function isValid2($vals) {
        if (isset ( $this->_rules )) {
            foreach ( $this->_rules as $input => $validators ) {
                $val = '';
                if (isset ( $vals [$input] )) {
                    $val = $vals [$input];
                }
                foreach ( $validators as $method => $param ) {
                    if (! Validaters::isValid ( $method, $val, $param )) {
                        if (substr ( $method, 0, 1 ) == '_') {
                            $method = substr ( $method, 1 );
                        }
                        $this->_errors [$input] [$method] = true;
                    }
                }
            }
        }
        
        return ! empty ( $this->_errors );
    }
    public function getErrors() {
        return $this->_errors;
    }
}
