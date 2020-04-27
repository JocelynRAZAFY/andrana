<?php
/**
 * Created by PhpStorm.
 * User: jocelyn
 * Date: 5/1/19
 * Time: 9:31 AM
 */

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Image;
use Knp\Snappy\Pdf;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mercure\Jwt\StaticJwtProvider;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;

class ToolsService
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var
     */
    private $targetDirectory;
    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var Pdf
     */
    private $pdf;

    private $image;

    public function __construct(
        ContainerInterface $container,
        KernelInterface $kernel,
        $targetDirectory,
        Pdf $pdf,
        Image $image
    )
    {
        $fileSystem = new Filesystem();
        $this->fs = $fileSystem;
        $this->container = $container;
        $this->kernel = $kernel;
        $this->targetDirectory = $targetDirectory;
        $this->pdf = $pdf;
        $this->image = $image;
    }

    /**
     * Get a user from the Security Token Storage.
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see TokenInterface::getUser()
     *
     * @final
     */
    public function getUser()
    {
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }

    public function getLogo($isSocial,$email,$isLocal)
    {
        if($isSocial){
            if (strpos($email, 'facebook')){
                $fileName = 'logo-fb.jpg';
            }else {
                $fileName = 'logo-google.png';
            }
        }else {
            $fileName = 'logo-net.png';
        }

        if($isLocal){
            return join('/',['/images','logo',$fileName]);
        }else{
            return join('/',['/sales5/public','images','logo',$fileName]);
        }
    }
    /**
     * @param $base64String
     * @return string
     */
    public function uploadBase64($base64String,string $uploadDirectory,string $imagesDir,string $email, ?string $oldImage = null)
    {

        //$ext = 'jpeg';
        $ext = explode(';',explode('/',explode(',',$base64String)[0])[1])[0];
        $fileName = md5(time().uniqid()).'.'.$ext;

        if(preg_match('/data:image/',$base64String)){
            $fileContent = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));//explode(',',$base64String)[1];
        }else {
            $fileContent = base64_decode($base64String);
        }

        if(strpos($uploadDirectory,'_html') === false){
            $avatar = join('/',[explode('/public',$uploadDirectory)[1],$imagesDir,$fileName]);
        }else{
            $avatar = join('/',['/sales5/public',$email,$imagesDir,$fileName]);
        }

        $imagesDirectory = join('/',[$uploadDirectory,$imagesDir]);
        $imagesFileName = join('/',[$uploadDirectory,$imagesDir,$fileName]);

        if(!is_dir($imagesDirectory)){
            $this->fs->mkdir($imagesDirectory, 0777);
        }

        file_put_contents($imagesFileName, $fileContent);

        if ($oldImage) {
            if(strpos($uploadDirectory,'_html') === false){
                $oldPath = explode('/public',$uploadDirectory)[0].'/public'.$oldImage;
            }else{
                $oldPath = explode('/sales5',$uploadDirectory)[0].$oldImage;
            }
            if (is_file($oldPath)) {
                $this->fs->remove($oldPath);
            }
        }

        return $avatar;

    }

    /**
     * @param $photo
     * @return string
     */
    public function imageToBase64($photo)
    {
        if($photo != ''){
            $urlImage = $this->targetDirectory.'/'.$photo;
            $type = pathinfo($urlImage, PATHINFO_EXTENSION);
            $data = file_get_contents($urlImage);

            return  'data:image/' . $type . ';base64,' . base64_encode($data);
        }

    }

    public function upload(UploadedFile $file,string $uploadDirectory,string $email, ?string $oldImage = null)
    {
        $idImage = md5(uniqid());
        $extension = $file->guessExtension();
        $fileName = "$idImage.$extension";

        if(strpos($uploadDirectory,'_html') === false){
            $avatar = join('/',[explode('/public',$uploadDirectory)[1],'images',$fileName]);
        }else{
            $avatar = join('/',['/sales/public',$email,'images',$fileName]);
        }

        $imagesDirectory = join('/',[$uploadDirectory,'images']);

        if(!is_dir($uploadDirectory)){
            $this->fs->mkdir($imagesDirectory, 0775);
        }

        try {
            $file->move($imagesDirectory, $fileName);
        } catch (FileException $e) {
            dump($e);die;
            new Exception("Erreur upload image ${e}");
        }

        if ($oldImage) {
            if(strpos($uploadDirectory,'_html') === false){
                $oldPath = explode('/public',$uploadDirectory)[0].'/public'.$oldImage;
            }else{
                $oldPath = explode('/sales',$uploadDirectory)[0].$oldImage;
            }
            if (is_file($oldPath)) {
                $this->fs->remove($oldPath);
            }
        }

        return $avatar;
    }

    public function removeImage(string $urlImage)
    {
        if(file_exists($urlImage)){
            unlink($urlImage);
        }
    }


    /**
     * Public function generate uuid v4
     *
     * @return string
     */
    public static function generateUUIDV4()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * @param $data
     * @return array
     */
    public function getDataFormat($data){

        return [
            'links' => [
                'pagination' => [
                    'total' => 50,
                    'per_page' => 3,
                    'current_page' => 1,
                    'last_page' => 2,
                    'next_page_url' => '...',
                    'prev_page_url' => '...',
                    'from' => 1,
                    'to' => 3,
                ]
            ],
            'data' => $data
        ];
    }

    /**
     * @param $dir
     * @return array
     */
    public function getListFilenameDir($dir)
    {
        $files = scandir($dir);
        $filenames = [];
        foreach ($files as $file){
            if(pathinfo($file, PATHINFO_EXTENSION) == 'php') {
                $filenames[] = basename($file, ".php");
            }
        }

        return $filenames;
    }

    public function copyBaseFileToNewProject(string $fileSource,string $dirDest,
                                             string $fileDest,string $entityName,
                                             string $typeSrc,string $extension, string $key)
    {
//        dump($fileDest);die();
//        $options=array('folderPermission'=>0755,'filePermission'=>0755);
        try {
            //chmod($fileDest,0777);
            copy($fileSource, $fileDest);

        }catch (\Exception $exception){
            return $exception;
        }
        // if (!copy($fileSource, $fileDest)) return false;
        // return $fileSource;
//        chmod($dirDest,$options['folderPermission']);

        $fileNew = $dirDest.'/'.$entityName.''.$typeSrc.'.'.$extension;

        if($key == 'index' || $key == 'table' || $key == 'fiche'){
            $dirModele = $dirDest.'/'.strtolower($entityName);
            if(!file_exists($dirModele)){
                mkdir($dirModele);
            }
            switch ($key){
                case 'table':
                    $fileNew =  $dirModele."/Table$entityName".'.'.$extension;
                    break;

                case 'index':
                    $fileNew =  $dirModele.'/index'.'.'.$extension;
                    break;

                case 'fiche':
                    $fileNew =  $dirModele."/Fiche$entityName".'.'.$extension;
                    break;
            }
        }

        if($key == 'store'){
            $fileNew = strtolower($fileNew);
        }

        // rename ("/folder/file.ext", "newfile.ext");
        if (!rename ($fileDest,$fileNew )) return false;

        return $fileNew;
    }

    public function updateContentsFile(string $fileRename,string $entityName, array $properties,
                                       string $update,string $typeSrc,string $key)
    {
        // minuscule
        $strEntityName = strtolower($entityName);
        $str = file_get_contents($fileRename);

        if($key == 'entity'){
            $str = str_replace('Template','Entity',$str);
            $item = $this->addItemEntity($entityName,$properties);
            $str = str_replace("const private = '';",$item,$str);
        }
        if($key == 'entityBase'){
            $str = str_replace('Template','EntityBase',$str);
            $item = $this->addItemEntity($entityName,$properties);
            $str = str_replace("const private = '';",$item,$str);
        }

        if($typeSrc == 'Controller'){
            $str = str_replace('Template',$typeSrc,$str);
            $value = sprintf('App\Manager\\%sManager',$entityName);
            $str = str_replace('App\Controller\ModeleManager',$value,$str);
        }

        $str = str_replace('Modele',$entityName,$str);
        $str = str_replace('modele',$strEntityName,$str);

        if($typeSrc == 'Manager'){
            $str = str_replace('Template',$typeSrc,$str);
            $value = sprintf('App\Repository\\%sRepository',$entityName);
            $search = 'App\Manager\\'.$entityName.'Repository';

            $str = str_replace($search,$value,$str);
            $str = str_replace('$update = "";',$update,$str);
            $str = str_replace('use App\Manager\BaseManager;','',$str);
        }

        if($typeSrc == 'Repository'){
            $str = str_replace('Template',$typeSrc,$str);
            $item = $this->addItemRepo($entityName,$properties);
            $str = str_replace('$return = \'\';',$item,$str);
        }

        if($key == 'store'){
            $item = $this->addItemStore($entityName,$properties);
            $str = str_replace('const itemValue = null',$item,$str);
        }

        if($key == 'index'){
            $item = $this->addItemData($entityName,$properties);
            $str = str_replace("{'cle': 'value'}",$item,$str);
        }

        if($key == 'table'){
            $itemColumn = $this->addItemColumn($entityName,$properties);
            $str = str_replace('"<span></span>"',$itemColumn,$str);
        }

        if($key == 'fiche'){
            $itemColumn = $this->addItemForm($entityName,$properties);
            $itemTest = $this->addItemFormTest($entityName,$properties);
            $itemHandle = $this->addItemFormHandle($entityName,$properties);
            $str = str_replace('"<span></span>"',$itemColumn,$str);
            $str = str_replace('const test = null',$itemTest,$str);
            $str = str_replace('handleChange(value){}',$itemHandle,$str);
        }

        file_put_contents($fileRename,$str);
    }

    /**
     * @param string $entityName
     * @return array|string[]|null
     */
    public function getProprieties(string $entityName)
    {
// a full list of extractors is shown further below
        $phpDocExtractor = new PhpDocExtractor();
        $reflectionExtractor = new ReflectionExtractor();

// list of PropertyListExtractorInterface (any iterable)
        $listExtractors = [$reflectionExtractor];

// list of PropertyTypeExtractorInterface (any iterable)
        $typeExtractors = [$phpDocExtractor, $reflectionExtractor];

// list of PropertyDescriptionExtractorInterface (any iterable)
        $descriptionExtractors = [$phpDocExtractor];

// list of PropertyAccessExtractorInterface (any iterable)
        $accessExtractors = [$reflectionExtractor];

// list of PropertyInitializableExtractorInterface (any iterable)
        $propertyInitializableExtractors = [$reflectionExtractor];

        $propertyInfo = new PropertyInfoExtractor(
            $listExtractors,
            $typeExtractors,
            $descriptionExtractors,
            $accessExtractors,
            $propertyInitializableExtractors
        );

// see below for more examples
        //$class = YourAwesomeCoolClass::class;
        $properties = $propertyInfo->getProperties($entityName);
        $typeProp = [];
        foreach ($properties as $property){
            $types = $propertyInfo->getTypes($entityName,$property);
            $typeProp[] = [
                'property' => $property,
                'type' => $types[0]->getBuiltinType()
            ];
        }

//        dump($typeProp);die;
        return $typeProp;
    }

    public function addIsset(string $entityName, array $properties)
    {
        $update = "";
        $prefix = "$".strtolower($entityName);
        $data = "$"."this->data->".strtolower($entityName);
        foreach ($properties as $key => $propertie){
            if($key != 0){
                $property = $propertie['property'];
                $value1 = "$data->$property";
                if($propertie['type'] == 'object'){
                    $value2 = $prefix."->set".ucfirst($property)."(new \DateTime($data->$property));"."\n";
                }else{
                    $value2 = $prefix."->set".ucfirst($property)."($data->$property);"."\n";
                }

                $isset = "\t\t\t\t"."if(isset(%s)){"."\n";
                $isset.= "\t\t\t\t\t\t"."%s";
                $isset.= "\t\t\t\t"."}"."\n";
                $update.= sprintf("$isset\n", $value1,$value2);
            }
        }

//        $save = "$"."this->save($prefix)";
//        $update.= sprintf("%s\n",      $save);
        return $update;
    }

    public function addItemStore(string $entityName, array $properties)
    {
        $item = "";
        $entity = strtolower($entityName);
        foreach ($properties as $key => $propertie){
            $property = $propertie['property'];
            if($key != 0){
                $item.= "\t\t\t\t\t\t"."item.$property = $entity.$property"."\n";
            }
        }

        return $item;
    }

    public function addItemData(string $entityName, array $properties)
    {
        $item = " {"."\n";
        $entity = strtolower($entityName);
        foreach ($properties as $key => $propertie){
            $property = $propertie['property'];
            if($key == 0){
                $item.= "\t\t\t\t\t"."$property : 0,"."\n";
            }else{
                if($key == count($properties) -1 ){
                    $item.= "\t\t\t\t\t"."$property : null"."\n";
                }else{
                    $item.= "\t\t\t\t\t"."$property : null,"."\n";
                }
            }
        }

        $item .= "\t\t\t"." }";
        return $item;
    }

    public function addItemColumn(string $entityName, array $properties)
    {
        $item = "";
        $entity = strtolower($entityName);
        foreach ($properties as $key => $propertie){
            $mini = $propertie['property'];
            $maj = ucfirst($mini);

            if($key != 0){
                $item .= "\t\t\t\t"."<el-table-column"."\n";
                $item .= "\t\t\t\t\t\t"."prop=\"$mini\""."\n";
                $item .= "\t\t\t\t\t\t"."label=\"$maj\""."\n";
                $item .= "\t\t\t\t\t\t"."sortable>"."\n";
                $item .= "\t\t\t\t"."</el-table-column>"."\n";
            }
        }
        return $item;
    }

    public function addItemForm(string $entityName, array $properties)
    {
        $item = "";
        $entity = strtolower($entityName);
        foreach ($properties as $key => $propertie){
            $maj = ucfirst($propertie['property']);

            if($key != 0){
                $item .= "\t\t\t\t"."<el-form-item label=\"$maj\">"."\n";
                $item .= $this->addItemFormWithType($propertie,$entityName);
                $item .= "\t\t\t\t"."</el-form-item>"."\n";
            }
        }
        return $item;
    }

    public function addItemFormWithType($propertie,$entityName)
    {
        $mini = $propertie['property'];
        $maj = ucfirst($mini);
        $item = "";
        switch ($propertie['type']){
            case 'string':
                $item = "\t\t\t\t\t\t"."<el-input v-model=\"form$entityName.$mini\"></el-input>"."\n";
                break;
            case 'int':
                $item = "\t\t\t\t\t\t"."<el-input-number v-model=\"form$entityName.$mini\" @change=\"handleChange$maj\" :min=\"1\" :max=\"10\"></el-input-number>"."\n";
                break;
            case 'bool':
                $item = "\t\t\t\t\t\t"."<el-checkbox v-model=\"form$entityName.$mini\">$maj</el-checkbox>"."\n";
                break;
            case 'object':
                $item = "\t\t\t\t\t\t"."<el-date-picker v-model=\"form$entityName.$mini\" type=\"date\" placeholder=\"Pick a day\"></el-date-picker>"."\n";
                break;
        }
        return $item;
    }

    public function addItemFormTest(string $entityName, array $properties)
    {
        $item = "";
        $entity = strtolower($entityName);
        foreach ($properties as $key => $propertie){
            $property = $propertie['property'];
//            $mini = strtolower($property);
            $mini = $propertie['property'];
            $maj = ucfirst($property);

            if($key != 0){
                $item .= "\t\t\t\t"."if(this.form$entityName.$mini == null){"."\n";
                $item .= "\t\t\t\t\t\t"."this.notificationWarning('$maj')"."\n";
                $item .= "\t\t\t\t\t\t"."return false"."\n";
                $item .= "\t\t\t\t"."}"."\n";
            }
        }
        return $item;
    }

    public function addItemFormHandle(string $entityName, array $properties)
    {
        $item = "";
        $entity = strtolower($entityName);
        foreach ($properties as $key => $propertie){
            $property = $propertie['property'];
            $mini = strtolower($property);
            $maj = ucfirst($property);
            if($key != 0){
                if($propertie['type'] == 'int'){
                    $item .= "\t\t\t"."handleChange$maj(value){},"."\n";
                }
            }
        }
        return $item;
    }

    public function addItemRepo(string $entityName, array $properties)
    {
        //->format('yy-m-d')
        $item = "\t\t"."return ["."\n";
        $entity = "$".strtolower($entityName);
        foreach ($properties as $key => $propertie){
            $mini = $propertie['property'];
            $maj = ucfirst($mini);
            if($key != count($properties) - 1){
                if($propertie['type'] == 'object'){
                    $item .= "\t\t\t\t\t\t"."'$mini' => ".$entity."->get".$maj."()->format('yy-m-d'),"."\n";
                }else{
                    $item .= "\t\t\t\t\t\t"."'$mini' => ".$entity."->get".$maj."(),"."\n";
                }
            }else{
                if($propertie['type'] == 'object'){
                    $item .= "\t\t\t\t\t\t"."'$mini' => ".$entity."->get".$maj."()->format('yy-m-d'),"."\n";
                }else{
                    $item .= "\t\t\t\t\t\t"."'$mini' => ".$entity."->get".$maj."()"."\n";
                }
            }
        }

        $item .= "\t\t"."];"."\n";
        return $item;
    }

    public function addItemEntity(string $entityName, array $properties)
    {

        $item = "";
        foreach ($properties as $key => $propertie){
            $mini = $propertie['property'];
            $type = $propertie['type'];
            $length = $propertie['length'];
            $nullable = $propertie['nullable'];

            $item .= "\t"."/**"."\n";
            switch ($type){
                case 'string':
                    $item .= "\t"."* @ORM\Column(type=\"$type\", length=$length, nullable=$nullable)"."\n";
                    break;
                case 'integer':
                    $item .= "\t"."* @ORM\Column(type=\"$type\", nullable=$nullable)"."\n";
                    break;
                case 'decimal':
                    $item .= "\t"."* @ORM\Column(type=\"$type\", nullable=$nullable, precision=2, scale=1)"."\n";
                    break;
                case 'boolean':
                    $item .= "\t"."* @ORM\Column(type=\"$type\", nullable=$nullable"."\n";
                    break;
                case 'datetime':
                    $item .= "\t"."* @ORM\Column(type=\"$type\", nullable=$nullable)"."\n";;
                    break;
                case 'text':
                    $item .= "\t"."* @ORM\Column(type=\"$type\", nullable=$nullable)"."\n";;;
                    break;
            }
            $item .= "\t"."* @Assert\NotBlank(message=\"Champ obligatoire\")"."\n";
            $item .= "\t"."*/"."\n";
            $item .= "\t"."private $mini;"."\n";
        }

        return $item;
    }

    public function publisher($content)
    {
        $hubUrl = $this->container->getParameter('mercure_publish_url');
        $token = (new Builder())
            // set other appropriate JWT claims, such as an expiration date
            // could also include the security roles, or anything else
            ->set('mercure', ['subscribe' => [], 'publish' => ['*']])
            // don't forget to set this parameter! Test value: aVerySecretKey
            ->sign(new Sha256(), $this->container->getParameter('mercure_secret_key'))
            ->getToken();

        $publisher = new Publisher($hubUrl, new StaticJwtProvider($token));

        $update = new Update(
            'https://sales.com/users/dunglas',
            json_encode(['content' => $content]),
            []
        );
       // return $publisher($update);
    }

    private function renderHtml($template,$image,&$nameFile,$type)
    {

        try {
            $nameFile = $this->kernel->getProjectDir().'/public/generate/pdf/'.$nameFile.$type;
            if(file_exists($nameFile)){
                unlink($nameFile);
            }
            $this->pdf->setTimeout(10000);
            $this->pdf->setOption('user-style-sheet',realpath('app-assets/css/test.css'));
            $html = $this->container->get('twig')->render($template,['title' => 'Jocelyn','image' => $image], true);

            return $html;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    public function generatePdf(string $nameFile,string $template)
    {
        $image = realpath('app-assets/images/pages/login.png');
        $html = $this->renderHtml($template,$image,$nameFile,'.pdf');
        $this->pdf->generateFromHtml(
            $html,
            $nameFile
        );

        return new JsonResponse($nameFile);
    }

    public function generateImage(string $nameFile,string $template)
    {
        $image = realpath('app-assets/images/pages/login.png');
        $html = $this->renderHtml($template,$image,$nameFile,'.jpeg');
        $this->image->generateFromHtml(
            $html,
            $nameFile
        );
    }

    public function downloadPdf($nameFile,$template)
    {
        $image = realpath('app-assets/images/pages/login.png');
        $html = $this->renderHtml($template,$image,$nameFile,'.pdf');
        $pdfName = ToolsService::generateUUIDV4().'.pdf';
        return new PdfResponse(
            $this->pdf->getOutputFromHtml($html),
            $pdfName
        );
    }

    public function getNbPage(int $length,int $max)
    {
        $number = floor($length / $max);
        $modulo = $length % $max;

        if($modulo != 0){
            return $number + 1;
        }else {
            return $number;
        }
    }
}
