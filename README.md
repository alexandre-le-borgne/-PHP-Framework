
    private function obfuscateEmail($email)
    {
        $arobase = strpos($email, '@');
        $len = $arobase >= self::EMAIL_OBFUSCATOR_LEN ? $arobase - self::EMAIL_OBFUSCATOR_LEN : 0;
        return substr_replace($email, str_repeat('*', $len), self::EMAIL_OBFUSCATOR_LEN, $len);
    }

    private function obfuscateSMS($sms)
    {
        if(preg_match_all("/[0-9]/", $sms, $digits, PREG_SET_ORDER) == 10) {
            $tmp = [];
            foreach($digits as $digit) {
                $tmp[] = $digit[0];
            }
            return str_repeat('*', 10 - self::SMS_OBFUSCATOR_LEN) . implode(array_slice($tmp, -self::SMS_OBFUSCATOR_LEN));
        }
        return '';
    }
	
	 /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException if a custom resource is hidden by a resource in a derived bundle
     */
    public function locateResource($name, $dir = null, $first = true)
    {
        if ('@' !== $name[0]) {
            throw new \InvalidArgumentException(sprintf('A resource name must start with @ ("%s" given).', $name));
        }

        if (false !== strpos($name, '..')) {
            throw new \RuntimeException(sprintf('File name "%s" contains invalid characters (..).', $name));
        }

        $bundleName = substr($name, 1);
        $path = '';
        if (false !== strpos($bundleName, '/')) {
            list($bundleName, $path) = explode('/', $bundleName, 2);
        }

        $isResource = 0 === strpos($path, 'Resources') && null !== $dir;
        $overridePath = substr($path, 9);
        $resourceBundle = null;
        $bundles = $this->getBundle($bundleName, false);
        $files = array();

        foreach ($bundles as $bundle) {
            if ($isResource && file_exists($file = $dir.'/'.$bundle->getName().$overridePath)) {
                if (null !== $resourceBundle) {
                    throw new \RuntimeException(sprintf('"%s" resource is hidden by a resource from the "%s" derived bundle. Create a "%s" file to override the bundle resource.',
                        $file,
                        $resourceBundle,
                        $dir.'/'.$bundles[0]->getName().$overridePath
                    ));
                }

                if ($first) {
                    return $file;
                }
                $files[] = $file;
            }

            if (file_exists($file = $bundle->getPath().'/'.$path)) {
                if ($first && !$isResource) {
                    return $file;
                }
                $files[] = $file;
                $resourceBundle = $bundle->getName();
            }
        }

        if (count($files) > 0) {
            return $first && $isResource ? $files[0] : $files;
        }

        throw new \InvalidArgumentException(sprintf('Unable to find file "%s".', $name));
    }
	
	/**
     * {@inheritdoc}
     */
    public function getRootDir()
    {
        if (null === $this->rootDir) {
            $r = new \ReflectionObject($this);
            $this->rootDir = dirname($r->getFileName());
        }

        return $this->rootDir;
    }
	
	
	class FormsRepository  extends BaseRepository
{

    public function findAllByProfessionalUid($uid) {
        return $this->createCypherQuery()
            ->match('(:Professionals{uid::uid})<-[:lkFormsOwner]-(n:Forms)')
            ->set('uid', $uid)
            ->end('n')
            ->getList();
    }
    
    public function setDataForRecipient(Forms $form, Persons $person, string $data) {
        $query = $this->createCypherQuery()
            ->match('(f:Forms)-[l:lkFormsRecipients]->(p:Persons)')
            ->where('id(f) = :id AND p.uid = :uid');
        return $query->query($query->getQuery().' SET l.data = :data, l.updateDate = :date')
            ->set('id', $form->getId())
            ->set('uid', $person->getUid())
            ->set('data', $data)
            ->set('date', date('Y-m-d H:i:s'))
            ->end('l')
            ->getResult();
    }
    
    public function hasAnswered(Forms $form, Persons $person) {
        return $this->createCypherQuery()
            ->match('(f:Forms)-[l:lkFormsRecipients]->(p:Persons)')
            ->where('id(f) = :id AND p.uid = :uid AND l.data IS NOT NULL')
            ->set('id', $form->getId())
            ->set('uid', $person->getUid())
            ->end('count(l)')
            ->getOne();
    }

    public function getAnswers(Forms $form) {
        return $this->createCypherQuery()
            ->match('(f:Forms)-[l:lkFormsRecipients]->(p:Persons)')
            ->where('id(f) = :id AND l.data IS NOT NULL')
            ->set('id', $form->getId())
            ->end('l, p')
            ->getResult();
    }
}

class Forms
{
    /**
     * @OGM\Auto
     */
    protected $id;

    /**
     * @OGM\Property
     * @OGM\Index
     */
    protected $data;
    
    /**
     * @OGM\Property
     * @OGM\Index
     */
    protected $creationDate;
    
    /**
     * @OGM\Property
     * @OGM\Index
     */
    protected $updateDate;

    /**
     * @OGM\ManyToMany(relation="lkFormsRecipients")
     */
    protected $recipients;

    /**
     * @OGM\ManyToOne(relation="lkFormsOwner")
     */
    protected $owner;

    public function __construct(){
        $this->recipients = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return mixed
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * @return mixed
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * @param mixed $recipients
     */
    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;
    }

    public function addRecipient($recipient) {
        $this->recipients->add($recipient);
    }

    public function removeRecipient($recipient){
        $this->recipients->removeElement($recipient);
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

}

class Canonicalize
{
    private $unwantedLetters = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð',
        'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã',
        'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ',
        'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ',
        'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę',
        'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī',
        'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ',
        'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ',
        'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť',
        'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ',
        'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ',
        'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');

    private $wantedLetters = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O',
        'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c',
        'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u',
        'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D',
        'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g',
        'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K',
        'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o',
        'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S',
        's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W',
        'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i',
        'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');

    public function canonicalize($string){
        $string = str_replace($this->unwantedLetters, $this->wantedLetters, $string);
        $string = strtolower($string);
        $string = preg_replace("/[^a-z-' ]/", "", $string);
        $string = preg_replace("/[-']/", " ", $string);
        $string = preg_replace('!\s+!', ' ', $string);
        $string = trim($string);

        return $string;
    }
}
	
	class GenerateRandomToken
{
    private function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    public function generateToken($length = 100){
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max)];
        }
        return $token;
    }
}

class GetAddressPosition
{
    public function GetAddressPosition($address)
    {
        $address = urlencode($address);
        $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=" . $address;
        $response = file_get_contents($url);
        $json = json_decode($response, true);
        if (empty($json['results'])) return false;
        $lat = $json['results'][0]['geometry']['location']['lat'];
        $lng = $json['results'][0]['geometry']['location']['lng'];

        return array($lat, $lng);
    }
}

   private function sendCurlRequest($url, array $data) {
        switch(self::method) {
            case 'GET':
                if(!empty($data)) {
                    $url .= '?';
                    foreach($data as $key => $value) {
                        $url .= $key.'='.urlencode($value).'&';
                    }
                    $url = substr($url, 0, -1);
                }
                $this->curl->sendGetRequest($url);
                break;
            default:
                $this->curl->sendPostRequest($url, $data);
        }
    }
	
	
class CreateClientCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('oauth:client:create')
            ->setDescription('Create OAuth Client')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Client Name?'
            )
            ->addArgument(
                'redirectUri',
                InputArgument::REQUIRED,
                'Redirect URI?'
            )
            ->addArgument(
                'grantType',
                InputArgument::REQUIRED,
                'Grant Type?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $oauthServer = $container->get('fos_oauth_server.server');

        $name = $input->getArgument('name');
        $redirectUri = $input->getArgument('redirectUri');
        $grantsType = array_map('trim', explode(',', $input->getArgument('grantType')));

        $clientManager = $container->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setName($name);
        $client->setRedirectUris([$redirectUri]);
        $client->setAllowedGrantTypes($grantsType);
        $clientManager->updateClient($client);

        $output->writeln(sprintf("<info>The client <comment>%s</comment> was created with <comment>%s</comment> as public id and <comment>%s</comment> as secret</info>",
            $client->getName(),
            $client->getPublicId(),
            $client->getSecret()));
//
//        $customers = $container->get('neo4j.manager')->getRepository('ESynaps\Neo4jBundle\Entity\Users')->findAll();
//
//        foreach($customers as $customer) {
//            $queryData = [];
//            $queryData['client_id'] = $client->getPublicId();
//            $queryData['redirect_uri'] = $client->getRedirectUris()[0];
//            $queryData['response_type'] = 'code';
//            $authRequest = new Request($queryData);
//
//            $oauthServer->finishClientAuthorization(true, $customer, $authRequest, $grantType);
//
//            $output->writeln(sprintf("<info>Customer <comment>%s</comment> linked to client <comment>%s</comment></info>",
//                $customer->getId(),
//                $client->getName()));
//        }
    }
}


class RefreshTokenGrantExtension implements GrantExtensionInterface
{
    private $storage;
    private $doctrineManager;
    private $neo4jManager;
    private $passwordEncoder;
    private $class;

    public function __construct(IOAuth2Storage $storage, \Doctrine\ORM\EntityManager $doctrineManager, EntityManager $neo4jManager, UserPasswordEncoder $passwordEncoder, $class)
    {
        $this->storage = $storage;
        $this->doctrineManager = $doctrineManager;
        $this->neo4jManager = $neo4jManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->class = $class;
    }

    /**
     * @see OAuth2\IOAuth2GrantExtension::checkGrantExtension
     */
    public function checkGrantExtension(IOAuth2Client $client, array $input, array $authHeaders)
    {
        if(!($this->storage instanceof IOAuth2RefreshTokens)) {
            throw new OAuth2ServerException(OAuth2::HTTP_BAD_REQUEST, OAuth2::ERROR_UNSUPPORTED_GRANT_TYPE);
        }

        if(!$input["refresh_token"]) {
            throw new OAuth2ServerException(OAuth2::HTTP_BAD_REQUEST, OAuth2::ERROR_INVALID_REQUEST, 'No "refresh_token" parameter found');
        }

        $token = $this->storage->getRefreshToken($input["refresh_token"]);

        if($token === null || $client->getPublicId() !== $token->getClientId()) {
            throw new OAuth2ServerException(OAuth2::HTTP_BAD_REQUEST, OAuth2::ERROR_INVALID_GRANT, 'Invalid refresh token');
        }

        if($token->hasExpired()) {
            throw new OAuth2ServerException(OAuth2::HTTP_BAD_REQUEST, OAuth2::ERROR_INVALID_GRANT, 'Refresh token has expired');
        }

        // store the refresh token locally so we can delete it when a new refresh token is generated
        $this->oldRefreshToken = $token->getToken();

        /**
         * @var UsersRepository $repository
         */
        $repository = $this->neo4jManager->getRepository($this->class);
        $user = $repository->findOneByUid($token->getData());
        if(!$user) {
            throw new OAuth2ServerException(OAuth2::HTTP_BAD_REQUEST, OAuth2::ERROR_INVALID_CLIENT, 'User could not be fetch');
        }

        /**
         * @var Users $user
         */
        $user = $user->getEntity();

        return array(
            'scope' => $token->getScope(),
            'data' => $user,
        );
    }
}
$deviceDetector = new DeviceDetector($userAgent);
        $deviceDetector->parse();
        $deviceDetectorClient = $deviceDetector->getClient(); //chrome mobile 55.0, [type:'browser', name:'chrome mobile', short_name:'cm', 'version':'55.0','engine':'blink']
        $deviceDetectorOs = $deviceDetector->getOs(); // Android 6.0 [name:'android', short_name:'and', version:'6.0', platform:'']

        $userAgentParsed = [
            'brand' => $deviceDetector->getBrandName(),
            'client' => [
                'type' => $deviceDetectorClient['type'] ?? '',
                'name' => $deviceDetectorClient['name'] ?? '',
                'version' => $deviceDetectorClient['version'] ?? ''
            ],
            'device' => [
                'type' => $deviceDetector->getDeviceName(), // smartphone
                'brand' => $deviceDetector->getBrandName(), // samsung
                'name' => $deviceDetector->getModel(), // galaxy s6
            ],
            'os' => [
                'name' => $deviceDetectorOs['name'] ?? '',
                'version' => $deviceDetectorOs['version'] ?? '',
                'platform' => $deviceDetectorOs['platform'] ?? '',
            ]
        ];
		
		
class ZipCodeRegexTester implements RegexTesterInterface
{
    const ZIP_CODE_REGEX = [
        "US" => "^\d{5}([\-]?\d{4})?$",
        "UK" => "^(GIR|[A-Z]\d[A-Z\d]??|[A-Z]{2}\d[A-Z\d]??)[ ]??(\d[A-Z]{2})$",
        "DE" => "\b((?:0[1-46-9]\d{3})|(?:[1-357-9]\d{4})|(?:[4][0-24-9]\d{3})|(?:[6][013-9]\d{3}))\b",
        "CA" => "^([ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ])\ {0,1}(\d[ABCEGHJKLMNPRSTVWXYZ]\d)$",
        "FR" => "^(F-)?((2[A|B])|[0-9]{2})[0-9]{3}$",
        "IT" => "^(V-|I-)?[0-9]{5}$",
        "AU" => "^(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})$",
        "NL" => "^[1-9][0-9]{3}\s?([a-zA-Z]{2})?$",
        "ES" => "^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$",
        "DK" => "^([D-d][K-k])?( |-)?[1-9]{1}[0-9]{3}$",
        "SE" => "^(s-|S-){0,1}[0-9]{3}\s?[0-9]{2}$",
        "BE" => "^[1-9]{1}[0-9]{3}$"
    ];

    static function test(string $value, array $matches = null)
    {
        return preg_match("/" . self::ZIP_CODE_REGEX['FR'] . "/i", $value, $matches);
    }
}

class CurlRequest
{
    protected $curlRequest;
    protected $curlResponse;
    protected $curlRequestInfo;
    protected $responseStatus;

    /**
     * CurlRequest constructor.
     */
    public function __construct()
    {
        $this->curlRequest = curl_init();
        curl_setopt($this->curlRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curlRequest, CURLOPT_SSL_VERIFYPEER, false);
    }

    /**
     * @return resource
     */
    public function getCurlRequest()
    {
        return $this->curlRequest;
    }

    /**
     * @return mixed
     */
    public function getCurlResponse()
    {
        return $this->curlResponse;
    }

    /**
     * @return mixed
     */
    public function getCurlRequestInfo($criterion = null)
    {
        return $criterion ? $this->curlRequestInfo[$criterion] : $this->curlRequestInfo;
    }

    /**
     * @return mixed
     */
    public function getResponseStatus()
    {
        return $this->responseStatus;
    }

    public function sendGetRequest($url, $autoCloseConnection = true, $headers = []){
        curl_setopt($this->curlRequest, CURLOPT_URL, $url);
        curl_setopt($this->curlRequest, CURLOPT_HTTPHEADER, $headers);
        $this->curlResponse = curl_exec($this->curlRequest);
        $this->curlRequestInfo = curl_getinfo($this->curlRequest);
        $this->responseStatus = $this->curlRequestInfo['http_code'];
        if($autoCloseConnection){
            $this->close();
        }
    }

    public function sendDeleteRequest($url, $autoCloseConnection = true, $headers = []){
        curl_setopt($this->curlRequest, CURLOPT_URL, $url);
        curl_setopt($this->curlRequest, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($this->curlRequest, CURLOPT_HTTPHEADER, $headers);
        $this->curlResponse = curl_exec($this->curlRequest);
        $this->curlRequestInfo = curl_getinfo($this->curlRequest);
        $this->responseStatus = $this->curlRequestInfo['http_code'];
        if($autoCloseConnection){
            $this->close();
        }
    }

    public function sendPostRequest($url, $data, $autoCloseConnection = true, $headers = []){
        curl_setopt($this->curlRequest, CURLOPT_URL, $url);
        curl_setopt($this->curlRequest, CURLOPT_POST, 1);
        curl_setopt($this->curlRequest, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->curlRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curlRequest, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curlRequest, CURLOPT_HTTPHEADER, $headers);

        $this->curlResponse = curl_exec($this->curlRequest);
        $this->curlRequestInfo = curl_getinfo($this->curlRequest);
        $this->responseStatus = $this->curlRequestInfo['http_code'];
        if($autoCloseConnection){
            $this->close();
        }
    }

    public function sendPutRequest($url, $data, $autoCloseConnection = true, $headers = []){
        curl_setopt($this->curlRequest, CURLOPT_URL, $url);
        curl_setopt($this->curlRequest, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($this->curlRequest, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($this->curlRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curlRequest, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curlRequest, CURLOPT_HTTPHEADER, $headers);
        $this->curlResponse = curl_exec($this->curlRequest);
        $this->curlRequestInfo = curl_getinfo($this->curlRequest);
        $this->responseStatus = $this->curlRequestInfo['http_code'];
        if($autoCloseConnection){
            $this->close();
        }
    }

    public function close(){
        curl_close($this->curlRequest);
    }
}

class DateIntervalCalculation
{
    public function add(\DateInterval $d1, \DateInterval $d2) {
        foreach (str_split('ymdhis') as $prop)
        {
            $d1->$prop += $d2->$prop;
        }
        return $d1;
    }

    public function diff(\DateInterval $d1, \DateInterval $d2) {
        foreach (str_split('ymdhis') as $prop)
        {
            $d1->$prop -= $d2->$prop;
        }
        return $d1;
    }

    function compare_dateInterval(\DateInterval $interval1, $operator ,\DateInterval $interval2){
        $interval1_str = $interval1->format("%Y%M%D%H%I%S");
        $interval2_str = $interval2->format("%Y%M%D%H%I%S");
        //var_dump($interval1_str);
        //var_dump($interval2_str);
        switch($operator){
            case "<":
                return $interval1_str < $interval2_str;
            case ">":
                return $interval1_str > $interval2_str;
            case "==" :
                return $interval1_str == $interval2_str;
            default:
                return null;
        }
    }
}

    public function getMail($getAction, Notifications $notification)
    {
        $template = $this->getTemplate($getAction);

        $body = nl2br('<html><head></head><body>' . $this->twig->render($template->getContent(), $notification->toArray()) . '</body>');
        $head = $this->twig->render($template->getSubject(), $notification->toArray());
        return array('head' => $head, 'body' => $body);
    }
	
	class GenericSerializer
{
    private $es;

    /**
     * GenericSerializer constructor.
     * @param Manager $elasticsearchUsersManager
     */
    public function __construct(Manager $elasticsearchUsersManager)
    {
        $this->es = $elasticsearchUsersManager;
    }


    /**
     * Transform a string like a(a1, a2(a21, a22), a3), b(b1), c
     * into an array [a => [a1, a2 => [a21, a22], a3], b => [b1], c]
     * @param string $fields
     * @return array
     */
    function fieldsToArray($fields)
    {
        $data = array();
        $i = $start = 0;
        $part = '';
        foreach(str_split($fields) as $k => $char) {
            if($char == '(') {
                if(!$i) {
                    $start = $k;
                }
                ++$i;
            } elseif($char == ')') {
                --$i;
                if($i == 0) {
                    $end = $k;
                    $data[$part] = $this->fieldsToArray(substr($fields, $start + 1, $end - $start - 1));
                    $part = '';
                }
            } elseif($i == 0) {
                if($char == ',') {
                    if($part != '') {
                        $data[] = $part;
                    }
                    $part = '';
                } elseif($char != ' ') {
                    $part .= $char;
                }
            }
        }
        if($part != '') {
            $data[] = $part;
        }
        return $data;
    }


    private function hydrateElasticsearchEntityProperty(ElasticsearchEntity &$elasticsearchEntity)
    {
        if(!method_exists($elasticsearchEntity, 'getEntity')) return;

        /**
         * @var ElasticsearchEntity $elasticsearchEntity
         */
        $entity = $this->es->find(get_class($elasticsearchEntity->getEntity()), $elasticsearchEntity->getElasticsearchId());

//        $reflectionElasticsearchEntity = new \ReflectionClass($elasticsearchEntity);
//        $reflectionEntity = new \ReflectionClass($entity);
//
//        $elasticsearchEntityProperties = $reflectionElasticsearchEntity->getProperties();
//        foreach($elasticsearchEntityProperties as $elasticsearchEntityProperty){
//            $docComment = $elasticsearchEntityProperty->getDocComment();
//            if(strpos($docComment, '@ES\Property') === false) continue;
//            $elasticsearchEntityProperty->setAccessible(true);
//            if($elasticsearchEntityProperty->getValue($elasticsearchEntity) !== null) continue;
//
//            try {
//                $entityProperty = $reflectionEntity->getProperty($elasticsearchEntityProperty->getName());
//                $entityProperty->setAccessible(true);
//                if($entityProperty->getValue($entity) === null) continue;
//            }
//            catch(\ReflectionException $e) {
//                continue;
//            }
//            $elasticsearchEntityProperty->setValue($elasticsearchEntity, $entityProperty->getValue($entity));
//        }

        $elasticsearchEntity->elasticsearchEntity = $entity;
    }

    /**
     * Return a multidimensional array with properties of the $class instance of a class according to the $fields
     * @param $class
     * @param $fields
     * @return array
     */
    public function serialize($class, $fields)
    {
        if(!is_array($fields))
            $fields = $this->fieldsToArray($fields);

        // Ce que l'on veut sérialiser n'est pas un objet, on le retourne
        if(!is_object($class)) return $class;

        $reflectionClass = new \ReflectionClass($class);
        $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methodsName = array();

        // On récupère le nom des méthodes publiques de $class
        foreach($methods as $method) {
            $methodsName[] = $method->getName();
        }

        $data = array();
        foreach($fields as $k => $field) {
            if(is_array($field)) {
                $name = $this->snakeToCamel(trim($k), 'get');

                if(in_array($name, $methodsName)) {

                    // On récupère le retour de la fonction
                    $return = $class->$name();
                    // Si ce retour est un nouvel objet
                    if(is_object($return)) {

                        // Si cet objet est une ArrayCollection, on le transforme en array
                        if($return instanceof ArrayCollection) {
                            foreach($return->toArray() as $v) {
                                $data[$k][] = $this->serialize($v, $field);
                            }
                        } else {
                            $data[$k] = $this->serialize($return, $field);
                        }
                    } elseif(is_array($return)) {

                        // Sinon on ajoute aux données retournées le résultat de la fonction
                        foreach($field as $v) {
                            $data[$k] = $return[$v];
                        }
                    } else {
                        $data[$k] = null;
                    }
                }
            } else {
                $name = $this->snakeToCamel(trim($field), 'get');

                // Si un des champs voulu est dans le tableau des noms de méthodes de $class
                if(in_array($name, $methodsName)) {
                    $return = $class->$name();

                    if(is_object($return)) {
                        if($return instanceof \DateTime) {
                            $data[$field] = $return->format('Y-m-d H:i:s');
                        } else {
                            $data[$field] = [];
                        }
                    } else {
                        if($return === null && $class instanceof ElasticsearchEntity) {
                            try {
                                $property = $reflectionClass->getProperty($this->snakeToCamel($field));

                                $docComment = $property->getDocComment();
                                if(strpos($docComment, '@ES\Property') === false) {
                                    throw new \Exception();
                                }
                                if(!property_exists($class, 'elasticsearchEntity')) {
                                    $this->hydrateElasticsearchEntityProperty($class);
                                }
                                $reflectionClassElastic = new \ReflectionClass($class->elasticsearchEntity);
                                $propertyElastic = $reflectionClassElastic->getProperty($this->snakeToCamel($field));
                                $propertyElastic->setAccessible(true);
                                $data[$field] = $propertyElastic->getValue($class->elasticsearchEntity);
                            } catch(\Exception $e) {
                                $data[$field] = $return;
                            }
                        } else {
                            $data[$field] = $return;
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Transform 'a_string_representing_a_variable' into 'aStringRepresentingAVariable'
     * or with a prefix 'get' and the $val 'method_action' into 'getMethodAction'
     * @param string $val
     * @param null $prefix
     * @return string
     */
    public function snakeToCamel($val, $prefix = null)
    {
        $val = str_replace(' ', '', ucwords(str_replace('_', ' ', $val)));
        if($prefix) {
            $val = $prefix . $val;
        } else {
            $val = strtolower(substr($val, 0, 1)) . substr($val, 1);
        }
        return $val;
    }

    /**
     * Transform only get method name into Camel Case
     * @param $val
     * @return string
     */
    private function reverseSnakeToCamel($val)
    {
        $val = substr($val, 3);
        return strtolower(substr($val, 0, 1)) . substr($val, 1);
    }
}


class TemplateParser
{
    const INVALID = '';
    const SELECTOR = '{{[a-zA-Z.\s]+}}';

    public function parse($template, $data = array()) {
        return preg_replace_callback('/'.TemplateParser::SELECTOR.'/', function ($matches) use($data) {
            $parts = explode('.', substr($matches[0],2,-2));
            $datacursor = $data;
            foreach($parts as $part) {
                $datacursor = $datacursor[trim($part)];
            }
            if(!is_string($datacursor))
                return TemplateParser::INVALID;
            return $datacursor;
        }, $template);
    }
}


/**
 * Device
 *
 * @ORM\Table(name="device")
 * @ORM\Entity(repositoryClass="V1\DevicesBundle\Repository\DeviceRepository")
 */
class Device
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uid", type="string", length=255)
     */
    private $uid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login_date", type="datetime")
     */
    private $lastLoginDate;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, unique=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="user_agent", type="string", length=255, nullable=true)
     */
    private $userAgent;

    /**
     * @var string
     *
     * @ORM\Column(name="device", type="text", nullable=true)
     */
    private $device;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255, nullable=true)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="validated_by", type="string", length=255, nullable=false)
     */
    private $validateBy;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uid
     *
     * @param string $uid
     *
     * @return Device
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Device
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Device
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     *
     * @return Device
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @return string
     */
    public function getValidateBy()
    {
        return $this->validateBy;
    }

    /**
     * @param string $validateBy
     */
    public function setValidateBy($validateBy)
    {
        $this->validateBy = $validateBy;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return \DateTime
     */
    public function getLastLoginDate()
    {
        return $this->lastLoginDate;
    }

    /**
     * @param \DateTime $lastLoginDate
     */
    public function setLastLoginDate($lastLoginDate)
    {
        $this->lastLoginDate = $lastLoginDate;
    }

    /**
     * @return array
     */
    public function getDevice()
    {
        return json_decode($this->device, true);
    }

    /**
     * @param array $device
     */
    public function setDevice(array $device)
    {
        $this->device = json_encode($device);
    }
}

if(!preg_match('/^(.+@.+\..+)?$/i', $email)) {
            return new JsonResponse(['success' => false, 'message' => 'Email invalid'], 401);
        }
		
		
		private function xmlToArray(\DOMNode $root)
    {
        $result = array();

        if($root->hasAttributes()) {
            $attrs = $root->attributes;
            foreach($attrs as $attr) {
                $result['@attributes'][$attr->name] = $attr->value;
            }
        }

        if($root->hasChildNodes()) {
            $children = $root->childNodes;
            if($children->length == 1) {
                $child = $children->item(0);
                if($child->nodeType == XML_TEXT_NODE) {
                    $result['_value'] = $child->nodeValue;
                    return count($result) == 1
                        ? $result['_value']
                        : $result;
                }
            }
            $groups = array();
            foreach($children as $child) {
                if($child->nodeName == '#text') continue;
                if(!isset($result[$child->nodeName])) {
                    $result[$child->nodeName] = $this->xmlToArray($child);
                } else {
                    if(!isset($groups[$child->nodeName])) {
                        $result[$child->nodeName] = array($result[$child->nodeName]);
                        $groups[$child->nodeName] = 1;
                    }
                    $result[$child->nodeName][] = $this->xmlToArray($child);
                }
            }
        }

        return $result;
    }

    private function buildXml($method, $parameters)
    {
        $xml = '<ns1:' . $method . '>';
        foreach($parameters as $name => $value) {
            $name = lcfirst(preg_replace_callback("/(?:^|_)([a-z])/", function($matches) {
                return strtoupper($matches[1]);
            }, $name));

            $xml .= "<ns1:$name>$value</ns1:$name>";
        }
        $xml .= '</ns1:' . $method . '>';

        return $xml;
    }

    /**
     * Remplace le buildXml simple qui ne supporte pas les noms composés de mots uniquement en capitales
     * et les sous niveaux d'un document XML
     *
     * Convertir un tableau en chaîne XML comme suit :
     *
     *
     * Tableau initial :
     *
     * $data = [
     *      'Entete' => [
     *          'metadonneesDPPR' => [
     *              'invalidation' => 'non',
     *              [
     *                  'dateActe' => '2007-02-21',
     *                  'dateCreation' => '2007-02-21',
     *                  'donneesComplementaire' => [
     *                      'donneeComplementaire' => [
     *                          'titre' => 'pieceSynthese',
     *                          [
     *                              'valeur' => 'oui'
     *                          ]
     *                      ]
     *                  ]
     *              ]
     *          ],
     *          'contenu' => [
     *              'typeMime' => 'doc',
     *              'R0lGODlh8ADwAOYAAGZmZvfvm8rEgIyRk/f8/r3W5mpgvW6kXaW8zZmjp7zCtL'
     *          ]
     *      ]
     * ];
     *
     *
     * Chaîne XML :
     *
     * <sisra:Entete>
     *      <sisra:metadonneesDPPR invalidation="non">
     *          <sisra:dateActe>2007-02-21</sisra:dateActe>
     *          <sisra:dateCreation>2007-02-21</sisra:dateCreation>
     *          <sisra:donneesComplementaire>
     *              <sisra:donneeComplementaire titre="pieceSynthese">
     *                  <sisra:valeur>oui</sisra:valeur>
     *              </sisra:donneeComplementaire>
     *          </sisra:donneesComplementaire>
     *      </sisra:metadonneesDPPR>
     *      <sisra:contenu typeMime="doc">
     *          R0lGODlh8ADwAOYAAGZmZvfvm8rEgIyRk/f8/r3W5mpgvW6kXaW8zZmjp7zCtL
     *      </sisra:contenu>
     * </sisra:Entete>
     *
     *
     * @param $data
     * @param string $namespace
     * @return string
     * @internal param $name
     * @internal param $value
     */
    private function buildComplexXml($data, $namespace = 'ns1')
    {
        $xml = '';
        foreach($data as $key => $value) {
            $attr = '';
            $content = '';
            if(is_array($value)) {
                if(count($value) == 1) {
                    $content = $this->buildComplexXml($value, $namespace);
                } else {
                    foreach($value as $k => $v) {
                        if(is_string($k) && is_string($v)) {
                            if(is_numeric($key))
                                $content .= $this->buildComplexXml([$k => $v], $namespace);
                            else
                                $attr .= ' ' . $k . '=' . '"' . $v . '"';
                        } else if(is_array($v)) {
                            $content .= $this->buildComplexXml([$k => $v], $namespace);
                        } else {
                            $content = $v;
                        }
                    }
                }
                if(is_numeric($key))
                    return $content;
            } else {
                if(is_numeric($key))
                    return $value;
                $content = $value;
            }
            $xml .= '<' . $namespace . ':' . $key . $attr . '>' . $content . '</' . $namespace . ':' . $key . '>';
        }
        return $xml;
    }

	$client = new \SoapClient($wsdl, ['trace' => true, 'exceptions' => true]);
	 $xml = $this->buildXml('InscrirePatient', $parametersValue);

        $data = new \SoapVar($xml, XSD_ANYXML);
        ini_set('default_socket_timeout', 30);
        $client->__soapCall('InscrirePatient', array($data));
        $data = $this->getLastResponse($client);
		
    private function getLastResponse(SoapClient $client)
    {
        $response = $client->__getLastResponse();

        $domDocument = new DOMDocument();
        $domDocument->loadXML($response);
        return $this->xmlToArray($domDocument);
    }
	
	/* @param Hydrater $hydrater
     * @param array $types
     */
    public function addHydrater(Hydrater $hydrater, array $types = []) {
        foreach($types as $type) {
            if($this->isValidType($type))
                $this->hydraters[$type][] = $hydrater;
        }
        
    }
	
	abstract class Hydrater
{
    abstract public function hydrate(Wall $row);
}
	
	
	 /**
     * @var array
     */
    private $hydraters = [];

    public function __construct(HydratersLoader $hydratersLoader)
    {

        $hydratersLoader->loadHydraters($this);
    }
	class HydratersLoader
{
    private $hydraters;

    public function __construct(EntityManager $em)
    {
        $this->hydraters = [
            [
                'hydrater' => new CommentsHydrater($em), 'types' => ['comments'],
            ]
        ];
    }
    
    public function loadHydraters(WallService $wallService) {
        foreach($this->hydraters as $hydrater) {
            if(!isset($hydrater['hydrater'], $hydrater['types'])) continue;
            if(!($hydrater['hydrater'] instanceof Hydrater)) continue;
            if(!is_array($hydrater['types'])) continue;

            $wallService->addHydrater($hydrater['hydrater'], $hydrater['types']);
        }
    }
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	----------------------------<?php
/**
 * Ce code va tester votre serveur pour déterminer quel serait le meilleur "cost".
 * Vous souhaitez définir le "cost" le plus élevé possible sans trop ralentir votre serveur.
 * 8-10 est une bonne base, mais une valeur plus élevée est aussi un bon choix à partir
 * du moment où votre serveur est suffisament rapide ! Le code suivant espère un temps
 * ≤ à 50 millisecondes, ce qui est une bonne base pour les systèmes gérants les identifications
 * intéractivement.
 */
$timeTarget = 0.05; // 50 millisecondes

$cost = 8;
do {
    $cost++;
    $start = microtime(true);
    password_hash("test", PASSWORD_DEFAULT, ["cost" => $cost]);
    $end = microtime(true);
} while (($end - $start) < $timeTarget);

echo "Valeur de 'cost' la plus appropriée : " . $cost . "\n";

# PHP-Framework

* app
    * framework
        * core
            * Controller.php
            * Entity.php
            * EntityManager.php
            * IDatabase.php
            * IPersistableEntity.php
            * Kernel.php
            * Model.php
            * PersistableEntity.php
            * PersistableModel.php
            * Request.php
            * Response.php
            * Route.php
            * Router.php
            * Session.php
            * View.php
            * ViewManager.php
            * ViewPart.php
        * databases
            * EmptyDatabase.php
            * SqlDatabase.php
        * exceptions
            * NotFoundException.php
            * TraceableException.php
        * services
            * GenericSerializer.php
            * Security.php
            * TimeConverter.php
    * App.php
* controller
* entity
* model
* services
* vendor
* views
    * core
        * exception.php
* web
    * css
    * img
    * inc
    * js
* index.php
