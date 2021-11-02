
<?php
namespace UnitTest\KR\GO\IROS;

$BasePath = realpath("../../../../Tilko.API/../");
require_once($BasePath . "/UnitTest/Constant.php");
require_once($BasePath . "/Tilko.API/REST.php");
require_once($BasePath . "/Tilko.API/Models/Models.php");

use Tilko\API;
use UnitTest;

// API 상세설명 URL
// https://tilko.net/Help/Api/POST-api-apiVersion-HometaxAgent-UTERNAA133-SuImNabSeJa-SuChulSilJeogMyeongSeSeo

try {
    $Constant = new \UnitTest\Constant;
    
    $Rest = new \Tilko\API\REST($Constant::ApiKey);
    $Rest->Init();
    
    // 홈택스의 수출실적명세서 조회 endPoint 설정
    $Rest->SetEndPointUrl($Constant::ApiHost . "api/v1.0/hometaxagent/uternaa133/suimnabseja/suchulsiljeogmyeongseseo");

     // 공동인증서 경로 설정
     $PublicPath = $Constant::CertPath . "/signCert.der";
     $PrivatePath = $Constant::CertPath . "/signPri.key";

     // Body 추가
     $Rest->AddBody("CertFile", _publicCert, true);                   // [암호화] 인증서 공개키(Base64 인코딩)
     $Rest->AddBody("KeyFile", _privateKey, true);                    // [암호화] 인증서 개인키(Base64 인코딩)
     $Rest->AddBody("CertPassword", Constant.CertPassword, true);     // [암호화] 인증서 암호(Base64 인코딩)
     $Rest->AddBody("AgentId", "", true);                             // [암호화] 세무대리인 ID(세무대리 관리번호가 있는 경우 / Base64 인코딩)
     $Rest->AddBody("AgentPassword", "", true);                       // [암호화] 세무대리인 암호(세무대리 관리번호가 있는 경우 / Base64 인코딩)
     $Rest->AddBody("BusinessNumber", "", true);                      // [암호화] 검색 할 사업자등록번호 또는 주민등록번호(xxxxxxxxxx 또는 xxxxxxxxxxxxx / Base64 인코딩)
     $Rest->AddBody("Year", "", false);                               // 검색년도(yyyy) 공백일 경우 검색 기준 해
     $Rest->AddBody("Quarter", "", false);                            // 검색분기(1분기 : 1 / 2분기 : 2 / 3분기 : 3 / 4분기 : 4) 공백일 경우 검색 기준 분기
     
     // API 호출
    define("Response", $Rest->Call());
    print("Response: " . Response);
}
catch (\Exception $e)
{
    print($e->getMessage());
}
?>
