<?php

/**
 * Sistema de cache
 *
 * @author Thiago Belem <contato@thiagobelem.net>
 * @link /
 */
class Cache {

    /**
     * Tempo padrão de cache
     *
     * @var string
     */
    private static $time = '5 minutes';

    /**
     * Local onde o cache será salvo
     *
     * Definido pelo construtor
     *
     * @var string
     */
    private $folder;

    /**
     * Construtor
     *
     * Inicializa a classe e permite a definição de onde os arquivos
     * serão salvos. Se o parâmetro $folder for ignorado o local dos
     * arquivos temporários do sistema operacional será usado
     *
     * @uses Cache::setFolder() Para definir o local dos arquivos de cache
     *
     * @param string $folder Local para salvar os arquivos de cache (opcional)
     *
     * @return void
     */
    public function __construct($folder = null) {
        $this->setFolder(!is_null($folder) ? $folder : sys_get_temp_dir());
    }

    /**
     * Define onde os arquivos de cache serão salvos
     *
     * Irá verificar se a pasta existe e pode ser escrita, caso contrário
     * uma mensagem de erro será exibida
     *
     * @param string $folder Local para salvar os arquivos de cache (opcional)
     *
     * @return void
     */
    protected function setFolder($folder) {
        // Se a pasta existir, for uma pasta e puder ser escrita
        if (file_exists($folder) && is_dir($folder) && is_writable($folder)) {
            $this->folder = $folder;
        } else {
            trigger_error('Não foi possível acessar a pasta de cache', E_USER_ERROR);
        }
    }

    /**
     * Gera o local do arquivo de cache baseado na chave passada
     *
     * @param string $key Uma chave para identificar o arquivo
     *
     * @return string Local do arquivo de cache
     */
    protected function generateFileLocation($key) {
        return $this->folder . DIRECTORY_SEPARATOR . sha1($key) . '.tmp';
    }

    /**
     * Cria um arquivo de cache
     *
     * @uses Cache::generateFileLocation() para gerar o local do arquivo de cache
     *
     * @param string $key Uma chave para identificar o arquivo
     * @param string $content Conteúdo do arquivo de cache
     *
     * @return boolean Se o arquivo foi criado
     */
    protected function createCacheFile($key, $content) {
        // Gera o nome do arquivo
        $filename = $this->generateFileLocation($key);
        // Cria o arquivo com o conteúdo
        return file_put_contents($filename, $content)
                OR trigger_error('Não foi possível criar o arquivo de cache', E_USER_ERROR);
    }

    /**
     * Salva um valor no cache
     *
     * @uses Cache::createCacheFile() para criar o arquivo com o cache
     *
     * @param string $key Uma chave para identificar o valor cacheado
     * @param mixed $content Conteúdo/variável a ser salvo(a) no cache
     * @param string $time Quanto tempo até o cache expirar (opcional)
     *
     * @return boolean Se o cache foi salvo
     */
    public function save($key, $content, $time = null) {
        $time = strtotime(!is_null($time) ? $time : self::$time);
        $content = serialize(array(
            'expires' => $time,
            'content' => $content));
        return $this->createCacheFile($key, $content);
    }

    /**
     * Salva um valor do cache
     *
     * @uses Cache::generateFileLocation() para gerar o local do arquivo de cache
     *
     * @param string $key Uma chave para identificar o valor cacheado
     *
     * @return mixed Se o cache foi encontrado retorna o seu valor, caso contrário retorna NULL
     */
    public function read($key) {
        $filename = $this->generateFileLocation($key);
        if (file_exists($filename) && is_readable($filename)) {
            $cache = unserialize(file_get_contents($filename));
            if ($cache['expires'] > time()) {
                return $cache['content'];
            } else {
                unlink($filename);
            }
        }
        return null;
    }

}
