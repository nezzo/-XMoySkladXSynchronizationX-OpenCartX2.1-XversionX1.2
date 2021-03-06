<?php
ini_set('display_errors',1);
error_reporting(E_ALL ^E_NOTICE);

include_once "/var/www/html/xhprof/xhprof_lib/utils/xhprof_lib.php";
include_once "/var/www/html/xhprof/xhprof_lib/utils/xhprof_runs.php";

#TODO ссылка на графическую схему профилирования
#http://localhost/xhprof/xhprof_html/callgraph.php?run=5a51413b94f7e&source=test


class ControllermodulemoyskladOC21Synch12 extends Controller {

    //храним url  МойСклад API
    public $urlAPI = "https://online.moysklad.ru/api/remap/1.1/";
    //тут будем хранить временные массивы данных
    public $cache_data = [];
    //тут будем хранить временные данные о количестве
    public $cahce_quantity = [];

    public function index() {
        
        $this->load->language('module/moyskladOC21Synch12');
        
        $this->document->addStyle('view/stylesheet/moyskladOC21Synch12.css');
        $this->document->addScript('view/javascript/jquery/tabs.js');
        
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('extension/module');

        $this->load->model('setting/setting');
         
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            
            $this->model_setting_setting->editSetting('moyskladOC21Synch12', $this->request->post);

            //после завершения функции делаем редирект в модуль
                $this->response->redirect($this->url->link('module/moyskladOC21Synch12', 'token=' . $this->session->data['token'], 'SSL'));

        }

        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['text_tab_setting'] = $this->language->get('text_tab_setting');
        $data['entry_save'] = $this->language->get('entry_save');
        $data['text_tab_import'] = $this->language->get('text_tab_import');
        $data['entry_import'] = $this->language->get('entry_import');
        $data['text_tab_author'] = $this->language->get('text_tab_author');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['setting_module'] = $this->language->get('setting_module');
        $data['import_text'] = $this->language->get('import_text');
        $data['import_button'] = $this->language->get('import_button');
        $data['entry_order_status_to_exchange'] = $this->language->get('entry_order_status_to_exchange');
        $data['entry_order_status_to_exchange_not'] = $this->language->get('entry_order_status_to_exchange_not');
        $data['download'] = $this->language->get('download');
        $data['download_image'] = $this->language->get('download_image');

        $data['text_tab_orders'] = $this->language->get('text_tab_orders');
        $data['text_order'] = $this->language->get('text_order');
        $data['export_order'] = $this->language->get('export_order');

        $data['import_quantity'] = $this->language->get('import_quantity');
        

 
 
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        else {
            $data['error_warning'] = '';
        }

         

        if (isset($this->error['moyskladOC21Synch12_username'])) {
            $data['error_moyskladOC21Synch12_username'] = $this->error['moyskladOC21Synch12_username'];
        }
        else {
            $data['error_moyskladOC21Synch12_username'] = '';
        }

        if (isset($this->error['moyskladOC21Synch12_order_status_to_exchange'])) {
            $data['error_moyskladOC21Synch12_order_status_to_exchange'] = $this->error['moyskladOC21Synch12_order_status_to_exchange'];
        }
        else {
            $data['error_moyskladOC21Synch12_order_status_to_exchange'] = '';
        }
        

        if (isset($this->error['moyskladOC21Synch12_password'])) {
            $data['error_moyskladOC21Synch12_password'] = $this->error['moyskladOC21Synch12_password'];
        }
        else {
            $data['error_moyskladOC21Synch12_password'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/moyskladOC21Synch12', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('module/moyskladOC21Synch12', 'token=' . $this->session->data['token'], 'SSL');

        //используем ссылку в форме для импорта товара
        $data['action_import'] = $this->url->link('module/moyskladOC21Synch12/getMethodImport', 'token=' . $this->session->data['token'], 'SSL');

        //используем ссылку в форме для загрузки картинок
        $data['action_get_images'] = $this->url->link('module/moyskladOC21Synch12/downloadImage', 'token=' . $this->session->data['token'], 'SSL');

        //используем ссылку в форме для выгрузки заказов
        $data['action_get_orders'] = $this->url->link('module/moyskladOC21Synch12/getOrders', 'token=' . $this->session->data['token'], 'SSL');

        //используем ссылку в форме для загрузки остатков по товарам
        $data['action_get_quantity'] = $this->url->link('module/moyskladOC21Synch12/getQuantity', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        

        if (isset($this->request->post['moyskladOC21Synch12_username'])) {
            $data['moyskladOC21Synch12_username'] = $this->request->post['moyskladOC21Synch12_username'];
        }
        else {
            $data['moyskladOC21Synch12_username'] = $this->config->get('moyskladOC21Synch12_username');
        }


        if (isset($this->request->post['moyskladOC21Synch12_order_status_to_exchange'])) {
            $data['moyskladOC21Synch12_order_status_to_exchange'] = $this->request->post['moyskladOC21Synch12_order_status_to_exchange'];
        }
        else {
            $data['moyskladOC21Synch12_order_status_to_exchange'] = $this->config->get('moyskladOC21Synch12_order_status_to_exchange');
        }


        if (isset($this->request->post['moyskladOC21Synch12_password'])) {
            $data['moyskladOC21Synch12_password'] = $this->request->post['moyskladOC21Synch12_password'];
        }
        else {
            $data['moyskladOC21Synch12_password'] = $this->config->get('moyskladOC21Synch12_password');
        }

       
        //получаем доступ к модели модуля и создаем таблицы в базе
        $this->load->model('tool/moyskladOC21Synch12');
        $this->model_tool_moyskladOC21Synch12->createTables();

        //получаем с базы количество картинок
        $data['count_image'] = $this->model_tool_moyskladOC21Synch12->countImage();


        //Подключаем все статусы ордеров
        $this->load->model('localisation/order_status');
        $order_statuses = $this->model_localisation_order_status->getOrderStatuses();
        foreach ($order_statuses as $order_status) {
            $data['order_statuses'][] = array(
                'order_status_id' => $order_status['order_status_id'],
                'name'            => $order_status['name']
            );
        }
 
        
        // Группы
        $this->load->model('customer/customer_group');
        $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
 
        $this->template = 'module/moyskladOC21Synch12.tpl';
        $this->children = array(
            'common/header',
            'common/footer' 
        );

        $data['heading_title'] = $this->language->get('heading_title');
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('module/moyskladOC21Synch12.tpl', $data));
 
    }

    private function validate() {

        if (!$this->user->hasPermission('modify', 'module/moyskladOC21Synch12')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;

    }

    //тут храниться инфа о клиенте
    protected function dataClient(){
        //получаем данные в переменные
        $mas = [
            "login" => (!empty($this->config->get('moyskladOC21Synch12_username'))) ? $this->config->get('moyskladOC21Synch12_username') : false,
            "pass" => (!empty($this->config->get('moyskladOC21Synch12_password'))) ? $this->config->get('moyskladOC21Synch12_password') : false
        ];

        return $mas;
    }

    //вызываем метод в форме
    public function getMethodImport(){
         if(!empty($_POST['start'])){
            # Инициализируем профайлер
            //xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);

            //чистим кэш остатки, что бы всегда свежие данные подгружались
            $this->deleteQuantityCache();

            //по клику запускаем API МойСклад для получения всего товара
            $this->getAllProduct(0);

            //после завершения функции делаем редирект в модуль
            $this->response->redirect($this->url->link('module/moyskladOC21Synch12', 'token=' . $this->session->data['token'], 'SSL'));

            /*
            $xhprof_data = xhprof_disable();

            $xhprof_runs = new XHProfRuns_Default();
            $run_id = $xhprof_runs->save_run($xhprof_data, "test");

            echo "http://localhost/xhprof/xhprof_html/index.php?run={$run_id}&source=test\n";
            */
         }

         return true;
    }
  
    //получаем весь товар, что есть (рекурсия)
    public function getAllProduct($position){
        $urlProduct = $this->urlAPI."entity/product?offset=$position&limit=100";
        $products = $this->restAPIMoySklad($urlProduct,0,"GET");
 

        //если дошли до конца списка то выходим из рекурсии 
        if(!empty($products["rows"])){
            
            $i = 0;

             foreach($products["rows"] as $product){
                
                //делаем провекру, что бы товар был с именем
                if(!empty($product["name"])){
                    $this->cache_data[] = $product;
                    
                }

                ++$i;

             }
           
              //передаем uuid для проверки существует ли такой uuid в базе или нет
              $this->searchUUID();

              //очищаем буфер данных
              unset($this->cache_data);

              //сохраняешь кэш о остатках
              $this->setQuantityCache();
              //очищаем буфер данных
              unset($this->cahce_quantity);
  

            //вызов рекурсии  
             $this->getAllProduct($position+$i);
        
        }
        

        return true; 
    }

    //делаем поиск в таблице uuid  на id  товара.
    //Если нету то добавляем товар если есть id  товара то обновляем.
    public function searchUUID(){
        //получаем доступ к модели модуля
        $this->load->model('tool/moyskladOC21Synch12');

        //формируем массив для хранения данных товаров
        $dat = $this->cache_data;

        //очищаем буфер данных
        unset($this->cache_data);

        $data = [];

        foreach ($dat as $mas){
        
            $findUUID = $this->model_tool_moyskladOC21Synch12->modelSearchUUID($mas["id"]);

            //проверяем есть ли картинка
            if(!empty($mas["image"]["meta"]["href"])){
                
                //заносим в массив данные о картинке (имя, ссылка)
                $image_data = [
                    "name_image"    =>  $mas["image"]["filename"],
                    "image_url"     =>  $mas["image"]["meta"]["href"]
                ];

                //добавляем инфу о кэше в базу
                $this->model_tool_moyskladOC21Synch12->addImagCache($image_data);

                $image =  'catalog/moysklad/'.$image_data["name_image"];

            }else{
                $image = "";
            }
            
            
            //проверяем существует ли цена продажи
            if(!empty($mas['salePrices'][0]['value'])){
          $price = number_format($mas['salePrices'][0]['value']/100, 2, '.', '');
            
            }else{
          $price = 0;
            }
     
            $data[] = [
                'findUUID'              =>  (!empty($findUUID['product_id'])) ? 
                                            $findUUID['product_id'] : 0,
                'model'                 =>  "",
                'sku'                   =>  "",
                'upc'                   =>  "",
                'ean'                   =>  "",
                'jan'                   =>  "",
                'isbn'                  =>  "",
                'mpn'                   =>  "",
                'location'              =>  "",
                'quantity'              =>  0,
                'minimum'               =>  "",
                'subtract'              =>  "",
                'stock_status_id'       =>  "",
                'date_available'        =>  "",
                'manufacturer_id'       =>  "",
                'shipping'              =>  "",
                'price'                 =>  $price,
                'points'                =>  "",
                'weight'                =>  (!empty($mas['weight'])) ? $mas['weight']: 0,
                'weight_class_id'       =>  "",
                'length'                =>  "",
                'width'                 =>  "",
                'height'                =>  "",
                'length_class_id'       =>  "",
                'status'                =>  1,
                'tax_class_id'          =>  "",
                'sort_order'            =>  "",
                'image'                 =>  $image,
                'product_description'   =>  [
                    $this->config->get('config_language_id') =>[
                        'name'          => $mas['name'],
                        'description'   => (!empty($mas['description'])) ? $mas['description']: " ",
                        'tag'           =>  "",
                        'meta_title'    =>  "",
                        'meta_description'  =>  "",
                        'meta_keyword'  =>  "",
                    ],
                ],
                'product_store'     =>[
                    'store_id'          => $this->config->get('config_store_id'),
                ],
                
                'uuid'                  =>  $mas["id"],
                'uuid_url'              =>  $mas['meta']['href'],
                'keyword'               =>  "",
     

            ];

            //сохраняем временные данные о количестве
            $this->cahce_quantity[] = [
                'uuid'  => $mas["id"],
                'name'  => $mas['name'],
            ];

        }
 
        foreach($data as $cache){
            //если нашли id товара то update, если нет то insert
            if(!empty($cache['findUUID'])){
                 $this->updateProduct($cache['findUUID'],$cache);
            }else{
                 $this->insertProduct($cache);
            }
        }
        
        return true;

    }
    
    
    //метод по обновлению инфы товара, параметр id товара
    public function updateProduct($id,$data){
        
        //получаем доступ к модели модуля
        $this->load->model('tool/moyskladOC21Synch12');
        $this->model_tool_moyskladOC21Synch12->updateProduct($id,$data);

        return true;
    }
    
    //метод по добавлению нового товара
    public function insertProduct($data){
         
        //подгружаем стандартный метод опенкарт по добавлению нового товара
        $this->load->model('catalog/product');
        $product_id = $this->model_catalog_product->addProduct($data);
        
        //получаем доступ к модели модуля
        $this->load->model('tool/moyskladOC21Synch12');
        
        //делаем проверку если товар добавлен то заносим его id  в таблицу uuid
        if(!empty($product_id)){
            $data = [
               'product_id' =>  $product_id,
               'uuid'       =>  $data['uuid'],
               'url'        =>  $data['uuid_url'],   
            ];
            
          //передаем массив в модель модуля  
         $this->model_tool_moyskladOC21Synch12->modelInsertUUID($data);
        }
        
        return true;
    }
    
   //функция по скачиванию картинок из моего склада
   function downloadImage(){

        //Ловим данные (количество картинок которые нужно скачать) и передаем функцию для скачивания
        if(!empty($_POST['count_images'])){

            //получаем доступ к модели модуля
            $this->load->model('tool/moyskladOC21Synch12');
            $masImage = $this->model_tool_moyskladOC21Synch12->getImage((int)$_POST['count_images']);
 
            //проверяем существует ли директория в которую будем заносить картинки, если нет то создаем
            if (empty(file_exists("../image/catalog/moysklad"))) {
                 $dir_image = mkdir("../image/catalog/moysklad", 0777);

                 //даем права на создание файлов
                 chmod("../image/catalog/moysklad", 0777);

                 //Если папка не создалась выводим false
                 if(empty($dir_image)){
                     $dir_image = false;

                 }else{
                    $dir_image = "../image/catalog/moysklad/";
                 }

            }else{
                $dir_image = "../image/catalog/moysklad/";
            }

            //проверяем создалась ли нами папка + есть ли картинки для скачивания
            if(!empty($masImage) && !empty($dir_image)){

                for($i = 0; $i < count($masImage); $i++){
 
                    $ch = curl_init($masImage[$i]['image_url']);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);  
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_USERPWD, $this->dataClient()['login'].":".$this->dataClient()['pass']);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
                        'Accept: application/octet-stream',
                        'Content-Type: application/octet-stream')                                                           
                    );   
                    $response = curl_exec($ch);
                    curl_close($ch);

                 
                    //проверяем нету ли ошибок на стороне сервера, если нету то загружаем картинку
                    if(!empty($response)){
                        file_put_contents('../image/catalog/moysklad/'.$masImage[$i]['name_image'], $response);
                    } 
                }

                $this->model_tool_moyskladOC21Synch12->delImage(count($masImage));
 
                //после завершения функции делаем редирект в модуль
                $this->response->redirect($this->url->link('module/moyskladOC21Synch12', 'token=' . $this->session->data['token'], 'SSL'));

                return true;
            }
        }
    }

    //получаем количество доступного товара в "Остатках"
    public function getQuantity(){
        //получаем доступ к модели модуля
        $this->load->model('tool/moyskladOC21Synch12');

        if(!empty($_POST['start'])){

            $getProductQuantity = $this->model_tool_moyskladOC21Synch12->getquantityCache();

            //проверяем есть ли кэш остатки
            if(!empty($getProductQuantity)){
                foreach ($getProductQuantity as $data){
                    $findUUID = $this->model_tool_moyskladOC21Synch12->modelSearchUUID($data["uuid"]);

                    //проверяем существует ли такой товар в базе
                    if(!empty($findUUID['product_id'])){
                        $jsonAnswerServer = $this->restAPIMoySklad($this->urlAPI."entity/assortment?filter=name=".urlencode($data['name']),0,"GET");
                        
                        //формируем результат по столбцу "Доступно" в моем складе
                        $quantity = (!empty($jsonAnswerServer['rows'][0]['quantity'])) ? $jsonAnswerServer['rows'][0]['quantity'] : 0;
                        
                        $this->model_tool_moyskladOC21Synch12->updateProductQuantity($findUUID['product_id'],$quantity);
     
                    }
                }

            }

            //чистим кэш остатки
            $this->deleteQuantityCache();

            //после завершения функции делаем редирект в модуль
            $this->response->redirect($this->url->link('module/moyskladOC21Synch12', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
         return true;
 
    }
    
    //заносим кэш "Остаток в базу"    
    public function setQuantityCache(){
        //получаем доступ к модели модуля
        $this->load->model('tool/moyskladOC21Synch12');
        
        //заносим в базу кэш об остатках
        foreach($this->cahce_quantity as $data){
            $this->model_tool_moyskladOC21Synch12->setquantityCache($data);
        }

        return true;
    }

    //очистка кэша остатки
    public function deleteQuantityCache(){
        //получаем доступ к модели модуля
        $this->load->model('tool/moyskladOC21Synch12');
        $this->model_tool_moyskladOC21Synch12->delquantityCache();

         return true;
    }

    //выгружаем все заказы в мойсклад
    public function getOrders(){
        //получаем из настроек какие ордера подгружать
        $order_status = $this->config->get('moyskladOC21Synch12_order_status_to_exchange');
        
        //по выбраном статусе загружаем заказы в мойсклад
        if(!empty($order_status)){
 
            //получаем доступ к модели модуля
            $this->load->model('tool/moyskladOC21Synch12');

            //удаляем с базы кэш контрагентов
            $this->model_tool_moyskladOC21Synch12->delContrAgent();

            //запуск на создание кэша контрагентов
            $this->addContrAgentCache(0);

            $this->load->model('sale/order');
        
        $orders = $this->model_tool_moyskladOC21Synch12->statusOrder($order_status);

        //массив который содержит информацию о заказе для моегосклада
        $info_order_mas = [];
        
        foreach ($orders as $orders_data){
            //получаем по ордер ид всю инфу о ордере
            $order = $this->model_sale_order->getOrder($orders_data['order_id']);

            //получаем ссылку на контрагента
            $urlSearchAgent = $this->model_tool_moyskladOC21Synch12->searchContrAgent(htmlspecialchars($order['firstname']." ".$order['lastname'])); 

            //формируем нужный нам массив для создания контрагента
            $new_contr_agent = [
                "name"          =>  $order['firstname']." ".$order['lastname'],
                "email"         =>  (!empty($order['email'])) ? $order['email'] : "",
                "phone"         =>  (!empty($order['telephone'])) ? $order['telephone'] : "",
                "actualAddress" =>  (!empty($order['payment_address_1'])) ? $order['payment_address_1'] : "",
                "legalAddress"  =>  (!empty($order['shipping_address_1'])) ? $order['shipping_address_1'] : "",
            ];

            //формируем массив для создания заказа
            $info_order_mas =   [
               "name"           =>  $order['order_id']."#".$_SERVER['HTTP_HOST'],
               "organization"   =>  [
                    "meta"  =>  [
                        "href"      =>  $this->getOrganization(0), //получаем ссылку на организацию
                        "type"      =>  "organization",
                        "mediaType" =>  "application/json"      
                    ],     
                ],
                "moment"        =>  $order['date_added'],
                "applicable"    =>  false,
                "vatEnabled"    =>  false,
                "agent"         =>  [
                    "meta"  =>  [
                        "href"  =>  (!empty($urlSearchAgent['url'])) ? $urlSearchAgent['url'] : $this->contrAgent(json_encode($new_contr_agent)), //получаем ссылку на контрагента 
                        "type"      =>  "counterparty",
                        "mediaType" =>  "application/json"     
                    ],    
                ],
            ];

            //формируем массив товара в 1 ордере
            $products = $this->model_sale_order->getOrderProducts($orders_data['order_id']);
 
            foreach ($products as $product) {
                $info_order_mas["positions"][]  =  [
                   "quantity"   =>  (float)$product['quantity'],
                   "price"      =>  (float)$product['price'] * 100, //цену передаем в копейках
                   "assortment" =>  [
                        "meta"  =>  [
                            "href"      => $this->model_tool_moyskladOC21Synch12->modelSearchUUIDUrl($product['product_id'])['url'], 
                            "type"      =>  "product",
                            "mediaType" =>  "application/json"
                        ],
                    ], 
                ];
 
            }

            //создаем заказ
            $this->setOrders(json_encode($info_order_mas));
 
        }
        }

        //после завершения функции делаем редирект в модуль
        $this->response->redirect($this->url->link('module/moyskladOC21Synch12', 'token=' . $this->session->data['token'], 'SSL'));

        return true;
    }

    //создаем заказ в мойсклад
    public function setOrders($data){
        $url = $this->urlAPI."entity/customerorder";
        $order = $this->restAPIMoySklad($url, $data, "POST");
 
       return true;

    }
    
    //создание контрагента при выгрузке заказов
    public function contrAgent($data){
        
        $url = $this->urlAPI."entity/counterparty";
        //возвращаем ссылку на контрагента (для подключения его в заказ)
        $json_contr_agent_id = $this->restAPIMoySklad($url,$data,"POST"); 
         return $json_contr_agent_id['meta']['href'];

    }

    //restAPI моего склада
    public function restAPIMoySklad($url,$data,$method){
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        //Делаем проверку, если данные есть для отправки то отправляем.
        if(!empty($data)){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $this->dataClient()['login'].":".$this->dataClient()['pass']);
        curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        // Only calling the head
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); // ADD THIS


        if(!empty($data)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))                                                                       
        );  
        }else{
          curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));    
        }

        $response = curl_exec($ch);
        curl_close($ch);
        
        //true  ставим, что бы получить массив, а не объект
        return json_decode($response, true);
    }

    //создаем кэш контрагентов
    public function addContrAgentCache($position){

        //получаем доступ к модели модуля
        $this->load->model('tool/moyskladOC21Synch12');

        $urlContrAgent = $this->urlAPI."entity/counterparty?offset=$position&limit=100";
 
        $contrAgents = $this->restAPIMoySklad($urlContrAgent,0,"GET"); 

        //если дошли до конца списка то выходим из рекурсии 
        if(!empty($contrAgents["rows"])){

            $i = 0;
 
            $data_contr = [];
            foreach($contrAgents["rows"] as $agent){
                //делаем провекру, что бы контрагент был ссылкой
                if(!empty($agent["meta"]["href"])){

                    //создаем массив который будет хранить данные кэша
                    $data_contr = [
                        "name"  =>  htmlspecialchars($agent['name']),
                        "url"   =>  $agent["meta"]["href"],
                    ];
 
                    //создаем кэш контрагентов
                    $this->model_tool_moyskladOC21Synch12->addCacheContrAgent($data_contr);
 
                }
                ++$i;
            }
            
            //вызов рекурсии  
            $this->addContrAgentCache($position+$i);
        }

        return true; 
    }

    //получаем первую Организацию (Юр. Лицо)
    public function getOrganization($position){
        $urlOrgan = $this->urlAPI."entity/organization?offset=$position&limit=1";
        $organization = $this->restAPIMoySklad($urlOrgan,0,"GET");

        //тут работаем без цикла так как получаем только 1 организацию
        return $organization['rows'][0]['meta']['href'];
    }


}
?>