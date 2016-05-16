<?php
/**
 * Small util to read and write .ini files
 *
 * @author      Damian Gomez
 * @version     1.0
 */
 
namespace Badbreze\Ini;

class SimpleIni
{
    /**
     * resulting array from file read
     * @var array
     */
    public $iniArray = array();

    /**
     * path to the ini file
     * @var string
     */
    public $iniFile = "";

    /**
     * Reads the ini file
     *
     * @param string $ini_file path to the ini file
     * @throws Exception
     */
    public function __construct(string $ini_file = null)
    {
        if (!$ini_file) {
            throw new Exception("Please select one ini file");
        }

        if (!file_exists($ini_file)) {
            throw new Exception("This file does not exists: " . $ini_file);
        }

        //Set ini location
        $this->iniFile = $ini_file;

        //Read the file
        $this->iniArray = parse_ini_file($ini_file, true);
    }

    /**
     * Ricerca una variabile all'interno del file di configurazione
     * @param string $variable Nome variabile cercata
     * @param string $section Sezione del file ini
     * @return string Valore della variabile
     * @throws Exception
     */
    public function getVariable(string $variable, string $section = null)
    {
        //Se mi viene passata lia il nome della variabile che la sezione in cui si trova
        if (!empty($variable) && !empty($section)) {
            //Verifica che la sezione esista
            if (isset($this->iniArray[$section])) {
                //verifico che esista la variabile
                if (isset($this->iniArray[$section][$variable])) {
                    $var = $this->iniArray[$section][$variable];
                    return is_numeric($var) ? (int) $var : $var;
                } else {
                    throw new Exception("This vasiavle does not exists: " . $variable);
                }
            } else {
                throw new Exception("The section soes not exists: " . $section);
            }
            //verifico di avere almeno la variabile
        } elseif (!empty($variable)) {
            //Scorro tutte le variabili e nodi dell'ini
            foreach ($this->iniArray as $key => $val) {
                if (isset($val[$variable])) {
                    return $val[$variable];
                }
            }

            throw new Exception("The variable does not exists: " . $variable);
        } else {
            throw new Exception("Please select the variable name");
        }
    }

    /**
     * Imposta un valore ful file .ini scelto
     * @param string $variable Nome variabile da impostare
     * @param string $section Sezione del file ini
     * @param mixed $value Valore variabile da impostare
     * @return bool true se la scrittura è avvenuta con successo
     * @throws Exception
     */
    public function setVariable(string $variable, string $section, string $value) {
        if (!empty($variable) && !empty($section)) {
            if(isset($this->iniArray[$section]) && isset($this->iniArray[$section][$variable])) {
                $this->iniArray[$section][$variable] = $value;

                $this->write_php_ini();
                return true;
            } else {
                throw new Exception("La variabile non esiste, e di default non viene creata, creala nel file .ini e riprova: ". $variable);
            }
        } else {
            throw new Exception("Devi indicare il nome della sezione e della variabile per proseguire");
        }
    }

    /**
     * Restituisce un array con tutti i valori della sezione scelta
     * @param string $section Sezione del file ini
     * @return array Array key-value contenente nome-valore delle variabili
     * @throws Exception
     */
    public function getSection(string $section)
    {
        if (!empty($section)) {
            if (isset($this->iniArray[$section])) {
                return $this->iniArray[$section];
            } else {
                throw new Exception("Non esiste la sezione: " . $section);
            }
        } else {
            throw new Exception("Devi passare un nome di sezione valido per poterla cercare nel .ini");
        }
    }

    /**
     * Imposta le variabili di un'intera sezione in base all'array passato
     * @param string $section Sezione del file ini
     * @param array $value Array key-value contenente nome-valore delle variabili da scrivere
     * @throws Exception
     */
    public function setSection(string $section, array $value) {
        if (!empty($section) && !empty($value) && is_array($value)) {
            if (isset($this->iniArray[$section])) {
                foreach($value as $key => $val) {
                     if (isset($this->iniArray[$section][$key])) {
                         $this->iniArray[$section][$key] = $val;
                     } else {
                         throw new Exception("Il seguente nodo non esiste e non viene creato di default: " . $key);
                     }
                }

                $this->write_php_ini();

                return true;
            } else {
                throw new Exception("Non esiste la sezione: " . $section);
            }
        } else {
            throw new Exception("Devi passare il nome della sezione e l'array con i valori da assegnare");
        }
    }

    /**
     * Scrive le informazioni sul file ini
     */
    function write_php_ini()
    {
        $res = array();

        foreach ($this->iniArray as $key => $val) {
            if (is_array($val)) {
                $res[] = "[$key]";
                foreach ($val as $skey => $sval)
                    $res[] = "$skey = " . (is_numeric($sval) ? $sval : '"' . (string) $sval . '"');
            } else
                $res[] = "$key = " . (is_numeric($val) ? $val : '"' . (string) $val . '"');
        }

        file_put_contents($this->iniFile, implode("\r\n", $res));
    }

}
