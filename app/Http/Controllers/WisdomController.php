<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWisdomRequest;
use App\Http\Requests\UpdateWisdomRequest;
use App\Models\User;
use App\Models\Wisdom;

class WisdomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function ajax($wisdoms)
    {
        $html = "";
        foreach ($wisdoms->items() as $wisdom) {
            $html .= view('shared.card')->with(compact('wisdom'))->render();
        }
        return $html;
    }
    public function index()
    {
        $wisdomsIds = array(
            array('id' => '34538'),
            array('id' => '36007'),
            array('id' => '36212'),
            array('id' => '34656'),
            array('id' => '12939'),
            array('id' => '11563'),
            array('id' => '10794'),
            array('id' => '36145'),
            array('id' => '17784'),
            array('id' => '36110'),
            array('id' => '36105'),
            array('id' => '10549'),
            array('id' => '35995'),
            array('id' => '15528'),
            array('id' => '36135'),
            array('id' => '10870'),
            array('id' => '15332'),
            array('id' => '34493'),
            array('id' => '17743'),
            array('id' => '11766'),
            array('id' => '36062'),
            array('id' => '9024'),
            array('id' => '23763'),
            array('id' => '22486'),
            array('id' => '36195'),
            array('id' => '11348'),
            array('id' => '13278'),
            array('id' => '36064'),
            array('id' => '14278'),
            array('id' => '24835'),
            array('id' => '36107'),
            array('id' => '36159'),
            array('id' => '35959'),
            array('id' => '36095'),
            array('id' => '35971'),
            array('id' => '36054'),
            array('id' => '36152'),
            array('id' => '35868'),
            array('id' => '36157'),
            array('id' => '16210'),
            array('id' => '9616'),
            array('id' => '40712'),
            array('id' => '34465'),
            array('id' => '19347'),
            array('id' => '29332'),
            array('id' => '25146'),
            array('id' => '25145'),
            array('id' => '34479'),
            array('id' => '35956'),
            array('id' => '24831'),
            array('id' => '36124'),
            array('id' => '33694'),
            array('id' => '36197'),
            array('id' => '36030'),
            array('id' => '36077'),
            array('id' => '44085'),
            array('id' => '35921'),
            array('id' => '36127'),
            array('id' => '17112'),
            array('id' => '35348'),
            array('id' => '17744'),
            array('id' => '10836'),
            array('id' => '35935'),
            array('id' => '36122'),
            array('id' => '36185'),
            array('id' => '36184'),
            array('id' => '36156'),
            array('id' => '35943'),
            array('id' => '34600'),
            array('id' => '39637'),
            array('id' => '39364'),
            array('id' => '36198'),
            array('id' => '25262'),
            array('id' => '35990'),
            array('id' => '27942'),
            array('id' => '36000'),
            array('id' => '35961'),
            array('id' => '36099'),
            array('id' => '25140'),
            array('id' => '25139'),
            array('id' => '25141'),
            array('id' => '36183'),
            array('id' => '35872'),
            array('id' => '36050'),
            array('id' => '35912'),
            array('id' => '34503'),
            array('id' => '22712'),
            array('id' => '36114'),
            array('id' => '36090'),
            array('id' => '28188'),
            array('id' => '34601'),
            array('id' => '35902'),
            array('id' => '36001'),
            array('id' => '36100'),
            array('id' => '36027'),
            array('id' => '35979'),
            array('id' => '20620'),
            array('id' => '2342'),
            array('id' => '34575'),
            array('id' => '35948'),
            array('id' => '35848'),
            array('id' => '44086'),
            array('id' => '36224'),
            array('id' => '34579'),
            array('id' => '36142'),
            array('id' => '12139'),
            array('id' => '36028'),
            array('id' => '35906'),
            array('id' => '34461'),
            array('id' => '35896'),
            array('id' => '36176'),
            array('id' => '36088'),
            array('id' => '36092'),
            array('id' => '36214'),
            array('id' => '34644'),
            array('id' => '20492'),
            array('id' => '26100'),
            array('id' => '36057'),
            array('id' => '20561'),
            array('id' => '35020'),
            array('id' => '28110'),
            array('id' => '14252'),
            array('id' => '36025'),
            array('id' => '34507'),
            array('id' => '35970'),
            array('id' => '16881'),
            array('id' => '35973'),
            array('id' => '35987'),
            array('id' => '35897'),
            array('id' => '19769'),
            array('id' => '14905'),
            array('id' => '25148'),
            array('id' => '25804'),
            array('id' => '25805'),
            array('id' => '34487'),
            array('id' => '14057'),
            array('id' => '17412'),
            array('id' => '16883'),
            array('id' => '10800'),
            array('id' => '21247'),
            array('id' => '34649'),
            array('id' => '17370'),
            array('id' => '35938'),
            array('id' => '25144'),
            array('id' => '36108'),
            array('id' => '34489'),
            array('id' => '36168'),
            array('id' => '35917'),
            array('id' => '1048'),
            array('id' => '14068'),
            array('id' => '14070'),
            array('id' => '34534'),
            array('id' => '35954'),
            array('id' => '35955'),
            array('id' => '19855'),
            array('id' => '34990'),
            array('id' => '34545'),
            array('id' => '27868'),
            array('id' => '24504'),
            array('id' => '19397'),
            array('id' => '33693'),
            array('id' => '34448'),
            array('id' => '16514'),
            array('id' => '36161'),
            array('id' => '24727'),
            array('id' => '35047'),
            array('id' => '19042'),
            array('id' => '34485'),
            array('id' => '21649'),
            array('id' => '36018'),
            array('id' => '36020'),
            array('id' => '35972'),
            array('id' => '36068'),
            array('id' => '36097'),
            array('id' => '14115'),
            array('id' => '36005'),
            array('id' => '21951'),
            array('id' => '34481'),
            array('id' => '35920'),
            array('id' => '35919'),
            array('id' => '34626'),
            array('id' => '36038'),
            array('id' => '36051'),
            array('id' => '35996'),
            array('id' => '36112'),
            array('id' => '36081'),
            array('id' => '27164'),
            array('id' => '36074'),
            array('id' => '35942'),
            array('id' => '36130'),
            array('id' => '36131'),
            array('id' => '14660'),
            array('id' => '36072'),
            array('id' => '36016'),
            array('id' => '36094'),
            array('id' => '35873'),
            array('id' => '14711'),
            array('id' => '34989'),
            array('id' => '34641'),
            array('id' => '26056'),
            array('id' => '9933'),
            array('id' => '11951'),
            array('id' => '34655'),
            array('id' => '17442'),
            array('id' => '35925'),
            array('id' => '18087'),
            array('id' => '36120'),
            array('id' => '19355'),
            array('id' => '25134'),
            array('id' => '35073'),
            array('id' => '35068'),
            array('id' => '35137'),
            array('id' => '36210'),
            array('id' => '35901'),
            array('id' => '19307'),
            array('id' => '36089'),
            array('id' => '36126'),
            array('id' => '35881'),
            array('id' => '35960'),
            array('id' => '9038'),
            array('id' => '34549'),
            array('id' => '35934'),
            array('id' => '18725'),
            array('id' => '35074'),
            array('id' => '35007'),
            array('id' => '36121'),
            array('id' => '19068'),
            array('id' => '35888'),
            array('id' => '9118'),
            array('id' => '8991'),
            array('id' => '35908'),
            array('id' => '17445'),
            array('id' => '25133'),
            array('id' => '25138'),
            array('id' => '35944'),
            array('id' => '25136'),
            array('id' => '35853'),
            array('id' => '20193'),
            array('id' => '19078'),
            array('id' => '36073'),
            array('id' => '36103'),
            array('id' => '30769'),
            array('id' => '19733'),
            array('id' => '35932'),
            array('id' => '22166'),
            array('id' => '11995'),
            array('id' => '28038'),
            array('id' => '34620'),
            array('id' => '36060'),
            array('id' => '9132'),
            array('id' => '9137'),
            array('id' => '9143'),
            array('id' => '19768'),
            array('id' => '36071'),
            array('id' => '36014'),
            array('id' => '36004'),
            array('id' => '36191'),
            array('id' => '19777'),
            array('id' => '35858'),
            array('id' => '27389'),
            array('id' => '11305'),
            array('id' => '36006'),
            array('id' => '35876'),
            array('id' => '35870'),
            array('id' => '36220'),
            array('id' => '34635'),
            array('id' => '36188'),
            array('id' => '36189'),
            array('id' => '34637'),
            array('id' => '36029'),
            array('id' => '36192'),
            array('id' => '36117'),
            array('id' => '36187'),
            array('id' => '35880'),
            array('id' => '36075'),
            array('id' => '36063'),
            array('id' => '19531'),
            array('id' => '35980'),
            array('id' => '35922'),
            array('id' => '36046'),
            array('id' => '36053'),
            array('id' => '35900'),
            array('id' => '16370'),
            array('id' => '9242'),
            array('id' => '36034'),
            array('id' => '35910'),
            array('id' => '19891'),
            array('id' => '9253'),
            array('id' => '25573'),
            array('id' => '36171'),
            array('id' => '19863'),
            array('id' => '10126'),
            array('id' => '10131'),
            array('id' => '36167'),
            array('id' => '19202'),
            array('id' => '9308'),
            array('id' => '35871'),
            array('id' => '29290'),
            array('id' => '7100'),
            array('id' => '27038'),
            array('id' => '35949'),
            array('id' => '36146'),
            array('id' => '17780'),
            array('id' => '10142'),
            array('id' => '35931'),
            array('id' => '33114'),
            array('id' => '29376'),
            array('id' => '35212'),
            array('id' => '22298'),
            array('id' => '36136'),
            array('id' => '35051'),
            array('id' => '36086'),
            array('id' => '35909'),
            array('id' => '19914'),
            array('id' => '1563'),
            array('id' => '1569'),
            array('id' => '22108'),
            array('id' => '25302'),
            array('id' => '25301'),
            array('id' => '36101'),
            array('id' => '27943'),
            array('id' => '36155'),
            array('id' => '27299'),
            array('id' => '34611'),
            array('id' => '36116'),
            array('id' => '35966'),
            array('id' => '19139'),
            array('id' => '29218'),
            array('id' => '36102'),
            array('id' => '20926'),
            array('id' => '36096'),
            array('id' => '34455'),
            array('id' => '35884'),
            array('id' => '10512'),
            array('id' => '36055'),
            array('id' => '12034'),
            array('id' => '36008'),
            array('id' => '36150'),
            array('id' => '35048'),
            array('id' => '35898'),
            array('id' => '35890'),
            array('id' => '25261'),
            array('id' => '16788'),
            array('id' => '36129'),
            array('id' => '35246'),
            array('id' => '36065'),
            array('id' => '22274'),
            array('id' => '36115'),
            array('id' => '17527'),
            array('id' => '20822'),
            array('id' => '36204'),
            array('id' => '21626'),
            array('id' => '35924'),
            array('id' => '35967'),
            array('id' => '35887'),
            array('id' => '36223'),
            array('id' => '17528'),
            array('id' => '36170'),
            array('id' => '22050'),
            array('id' => '36083'),
            array('id' => '35950'),
            array('id' => '36052'),
            array('id' => '764'),
            array('id' => '34613'),
            array('id' => '29599'),
            array('id' => '21873'),
            array('id' => '35957'),
            array('id' => '35066'),
            array('id' => '11157'),
            array('id' => '36158'),
            array('id' => '35886'),
            array('id' => '11703'),
            array('id' => '35993'),
            array('id' => '36033'),
            array('id' => '36017'),
            array('id' => '34590'),
            array('id' => '40432'),
            array('id' => '30966'),
            array('id' => '14738'),
            array('id' => '14739'),
            array('id' => '36177'),
            array('id' => '35867'),
            array('id' => '35851'),
            array('id' => '35855'),
            array('id' => '35869'),
            array('id' => '35933'),
            array('id' => '10717'),
            array('id' => '35845'),
            array('id' => '35866'),
            array('id' => '35846'),
            array('id' => '36194'),
            array('id' => '36196'),
            array('id' => '35847'),
            array('id' => '21370'),
            array('id' => '9721'),
            array('id' => '27781'),
            array('id' => '10411'),
            array('id' => '36221'),
            array('id' => '29467'),
            array('id' => '21234'),
            array('id' => '32189'),
            array('id' => '36147'),
            array('id' => '25269'),
            array('id' => '36134'),
            array('id' => '9499'),
            array('id' => '19990'),
            array('id' => '34505'),
            array('id' => '14781'),
            array('id' => '36009'),
            array('id' => '14788'),
            array('id' => '27179'),
            array('id' => '36032'),
            array('id' => '36002'),
            array('id' => '36218'),
            array('id' => '27268'),
            array('id' => '34499'),
            array('id' => '25964'),
            array('id' => '34985'),
            array('id' => '30037'),
            array('id' => '10460'),
            array('id' => '25204'),
            array('id' => '35002'),
            array('id' => '36013'),
            array('id' => '35841'),
            array('id' => '19089'),
            array('id' => '9450'),
            array('id' => '21995'),
            array('id' => '34495'),
            array('id' => '22256'),
            array('id' => '40412'),
            array('id' => '34581'),
            array('id' => '34583'),
            array('id' => '35042'),
            array('id' => '9746'),
            array('id' => '34459'),
            array('id' => '21519'),
            array('id' => '36160'),
            array('id' => '22036'),
            array('id' => '40519'),
            array('id' => '36143'),
            array('id' => '34527'),
            array('id' => '24096'),
            array('id' => '36169'),
            array('id' => '36166'),
            array('id' => '38966'),
            array('id' => '38037'),
            array('id' => '40883'),
            array('id' => '12290'),
            array('id' => '11318'),
            array('id' => '13938'),
            array('id' => '35882'),
            array('id' => '35856'),
            array('id' => '9672'),
            array('id' => '24552'),
            array('id' => '34991'),
            array('id' => '34532'),
            array('id' => '16409'),
            array('id' => '36045'),
            array('id' => '17489'),
            array('id' => '1815'),
            array('id' => '36023'),
            array('id' => '36153'),
            array('id' => '34525'),
            array('id' => '36165'),
            array('id' => '22410'),
            array('id' => '23113'),
            array('id' => '35859'),
            array('id' => '35860'),
            array('id' => '36206'),
            array('id' => '23114'),
            array('id' => '34632'),
            array('id' => '34483'),
            array('id' => '34523'),
            array('id' => '20697'),
            array('id' => '34598'),
            array('id' => '36119'),
            array('id' => '37902'),
            array('id' => '36217'),
            array('id' => '34553'),
            array('id' => '36174'),
            array('id' => '13943'),
            array('id' => '23756'),
            array('id' => '35003'),
            array('id' => '35913'),
            array('id' => '12324'),
            array('id' => '19734'),
            array('id' => '23397'),
            array('id' => '12325'),
            array('id' => '34622'),
            array('id' => '36026'),
            array('id' => '17494'),
            array('id' => '9767'),
            array('id' => '35031'),
            array('id' => '25859'),
            array('id' => '24900'),
            array('id' => '17898'),
            array('id' => '36222'),
            array('id' => '35894'),
            array('id' => '21028'),
            array('id' => '19780'),
            array('id' => '36066'),
            array('id' => '36067'),
            array('id' => '12342'),
            array('id' => '19343'),
            array('id' => '29486'),
            array('id' => '13719'),
            array('id' => '26148'),
            array('id' => '23846'),
            array('id' => '25174'),
            array('id' => '35969'),
            array('id' => '35968'),
            array('id' => '34521'),
            array('id' => '35044'),
            array('id' => '12359'),
            array('id' => '22772'),
            array('id' => '19760'),
            array('id' => '24899'),
            array('id' => '34516'),
            array('id' => '17435'),
            array('id' => '34647'),
            array('id' => '15083'),
            array('id' => '35861'),
            array('id' => '36219'),
            array('id' => '36172'),
            array('id' => '35903'),
            array('id' => '35930'),
            array('id' => '35939'),
            array('id' => '36015'),
            array('id' => '35001'),
            array('id' => '13315'),
            array('id' => '35041'),
            array('id' => '36209'),
            array('id' => '40659'),
            array('id' => '35946'),
            array('id' => '20733'),
            array('id' => '36154'),
            array('id' => '35879'),
            array('id' => '36022'),
            array('id' => '20480'),
            array('id' => '36163'),
            array('id' => '10381'),
            array('id' => '29571'),
            array('id' => '13730'),
            array('id' => '3987'),
            array('id' => '11212'),
            array('id' => '35941'),
            array('id' => '28216'),
            array('id' => '35994'),
            array('id' => '36040'),
            array('id' => '10763'),
            array('id' => '25862'),
            array('id' => '16882'),
            array('id' => '36098'),
            array('id' => '21637'),
            array('id' => '35045'),
            array('id' => '21638'),
            array('id' => '2510'),
            array('id' => '36113'),
            array('id' => '35046'),
            array('id' => '34618'),
            array('id' => '34596'),
            array('id' => '34444'),
            array('id' => '35991'),
            array('id' => '19378'),
            array('id' => '11114'),
            array('id' => '21352'),
            array('id' => '19112'),
            array('id' => '36031'),
            array('id' => '12996'),
            array('id' => '34661'),
            array('id' => '18971'),
            array('id' => '25026'),
            array('id' => '36048'),
            array('id' => '14378'),
            array('id' => '27990'),
            array('id' => '19502'),
            array('id' => '36012'),
            array('id' => '27300'),
            array('id' => '35974'),
            array('id' => '35988'),
            array('id' => '35981'),
            array('id' => '36190'),
            array('id' => '34562'),
            array('id' => '35937'),
            array('id' => '35907'),
            array('id' => '36039'),
            array('id' => '36037'),
            array('id' => '36024'),
            array('id' => '35865'),
            array('id' => '19941'),
            array('id' => '36076'),
            array('id' => '13001'),
            array('id' => '34467'),
            array('id' => '27290'),
            array('id' => '34551'),
            array('id' => '19312'),
            array('id' => '20632'),
            array('id' => '35927'),
            array('id' => '9214'),
            array('id' => '36041'),
            array('id' => '35875'),
            array('id' => '36149'),
            array('id' => '36139'),
            array('id' => '34453'),
            array('id' => '34560'),
            array('id' => '36202'),
            array('id' => '36140'),
            array('id' => '36047'),
            array('id' => '35989'),
            array('id' => '35962'),
            array('id' => '18977'),
            array('id' => '32363'),
            array('id' => '35964'),
            array('id' => '36211'),
            array('id' => '35037'),
            array('id' => '35005'),
            array('id' => '9610'),
            array('id' => '25996'),
            array('id' => '35025'),
            array('id' => '35006'),
            array('id' => '29762'),
            array('id' => '19145'),
            array('id' => '24903'),
            array('id' => '27293'),
            array('id' => '36132'),
            array('id' => '31111'),
            array('id' => '36213'),
            array('id' => '18793'),
            array('id' => '36010'),
            array('id' => '36225'),
            array('id' => '36203'),
            array('id' => '35998'),
            array('id' => '36087'),
            array('id' => '35039'),
            array('id' => '36093'),
            array('id' => '25150'),
            array('id' => '34497'),
            array('id' => '1247'),
            array('id' => '34808'),
            array('id' => '19392'),
            array('id' => '35916'),
            array('id' => '36003'),
            array('id' => '35952'),
            array('id' => '34982'),
            array('id' => '36079'),
            array('id' => '34557'),
            array('id' => '39163'),
            array('id' => '34628'),
            array('id' => '36141'),
            array('id' => '35843'),
            array('id' => '35899'),
            array('id' => '36019'),
            array('id' => '35889'),
            array('id' => '14578'),
            array('id' => '35923'),
            array('id' => '35864'),
            array('id' => '36044'),
            array('id' => '34451'),
            array('id' => '36216'),
            array('id' => '27991'),
            array('id' => '28719'),
            array('id' => '14589'),
            array('id' => '16912'),
            array('id' => '26157'),
            array('id' => '10183'),
            array('id' => '35250'),
            array('id' => '17434'),
            array('id' => '35862'),
            array('id' => '35928'),
            array('id' => '25149'),
            array('id' => '13802'),
            array('id' => '13801'),
            array('id' => '35891'),
            array('id' => '36208'),
            array('id' => '35947'),
            array('id' => '17458'),
            array('id' => '13164'),
            array('id' => '35953'),
            array('id' => '19364'),
            array('id' => '35963'),
            array('id' => '36043'),
            array('id' => '19726'),
            array('id' => '34540'),
            array('id' => '20256'),
            array('id' => '19101'),
            array('id' => '35992'),
            array('id' => '35929'),
            array('id' => '36199'),
            array('id' => '35854'),
            array('id' => '36186'),
            array('id' => '36178'),
            array('id' => '36128'),
            array('id' => '34592'),
            array('id' => '9840'),
            array('id' => '24286'),
            array('id' => '18648'),
            array('id' => '36123'),
            array('id' => '35985'),
            array('id' => '13494'),
            array('id' => '34988'),
            array('id' => '35844'),
            array('id' => '35033'),
            array('id' => '35905'),
            array('id' => '20884'),
            array('id' => '36125'),
            array('id' => '35911'),
            array('id' => '27934'),
            array('id' => '5096'),
            array('id' => '36059'),
            array('id' => '29499'),
            array('id' => '36175'),
            array('id' => '12540'),
            array('id' => '2652'),
            array('id' => '36180'),
            array('id' => '36021'),
            array('id' => '34577'),
            array('id' => '35940'),
            array('id' => '36042'),
            array('id' => '38257'),
            array('id' => '19133'),
            array('id' => '35977'),
            array('id' => '21592'),
            array('id' => '35984'),
            array('id' => '25253'),
            array('id' => '17495'),
            array('id' => '19713'),
            array('id' => '35857'),
            array('id' => '35976'),
            array('id' => '35849'),
            array('id' => '34547'),
            array('id' => '35997'),
            array('id' => '36138'),
            array('id' => '36085'),
            array('id' => '35893'),
            array('id' => '11227'),
            array('id' => '17443'),
            array('id' => '35877'),
            array('id' => '36200'),
            array('id' => '22493'),
            array('id' => '21572'),
            array('id' => '2678'),
            array('id' => '35895'),
            array('id' => '36137'),
            array('id' => '36049'),
            array('id' => '35885'),
            array('id' => '34542'),
            array('id' => '34477'),
            array('id' => '36215'),
            array('id' => '35983'),
            array('id' => '34966'),
            array('id' => '44371'),
            array('id' => '34566'),
            array('id' => '35978'),
            array('id' => '20410'),
            array('id' => '17551'),
            array('id' => '17532'),
            array('id' => '34573'),
            array('id' => '34297'),
            array('id' => '25143'),
            array('id' => '35874'),
            array('id' => '14624'),
            array('id' => '12785'),
            array('id' => '36173'),
            array('id' => '36058'),
            array('id' => '36104'),
            array('id' => '25142'),
            array('id' => '13858'),
            array('id' => '20002'),
            array('id' => '26962'),
            array('id' => '36148'),
            array('id' => '10167'),
            array('id' => '21928'),
            array('id' => '9888'),
            array('id' => '27682'),
            array('id' => '36036'),
            array('id' => '19398'),
            array('id' => '36207'),
            array('id' => '34986'),
            array('id' => '25265'),
            array('id' => '34642'),
            array('id' => '17772'),
            array('id' => '22068'),
            array('id' => '13968'),
            array('id' => '23237'),
            array('id' => '2048'),
            array('id' => '20065'),
            array('id' => '11404'),
            array('id' => '34442'),
            array('id' => '22626'),
            array('id' => '35986'),
            array('id' => '20966'),
            array('id' => '29563'),
            array('id' => '21645'),
            array('id' => '34446'),
            array('id' => '34501'),
            array('id' => '21460'),
            array('id' => '11049'),
            array('id' => '11050'),
            array('id' => '18979'),
            array('id' => '13405'),
            array('id' => '36205'),
            array('id' => '35945'),
            array('id' => '36151'),
            array('id' => '20830'),
            array('id' => '15325'),
            array('id' => '22727'),
            array('id' => '34511'),
            array('id' => '25245'),
            array('id' => '33676'),
            array('id' => '27270'),
            array('id' => '34536'),
            array('id' => '34653'),
            array('id' => '22209'),
            array('id' => '35878'),
            array('id' => '36069'),
            array('id' => '36070'),
            array('id' => '2718'),
            array('id' => '11054'),
            array('id' => '34651'),
            array('id' => '34440'),
            array('id' => '35982'),
            array('id' => '36179'),
            array('id' => '34603'),
            array('id' => '35345'),
            array('id' => '35346'),
            array('id' => '36084'),
            array('id' => '35958'),
            array('id' => '36201'),
            array('id' => '35999'),
            array('id' => '36011'),
            array('id' => '35842'),
            array('id' => '16369'),
            array('id' => '35347'),
            array('id' => '36078'),
            array('id' => '36118'),
            array('id' => '35936'),
            array('id' => '36061'),
            array('id' => '18740'),
            array('id' => '125'),
            array('id' => '12922'),
            array('id' => '13427'),
            array('id' => '34473'),
            array('id' => '17151'),
            array('id' => '36111'),
            array('id' => '34594'),
            array('id' => '21355'),
            array('id' => '26767'),
            array('id' => '20629'),
            array('id' => '34438'),
            array('id' => '8921'),
            array('id' => '34463'),
            array('id' => '34998'),
            array('id' => '28033'),
            array('id' => '17681'),
            array('id' => '34999'),
            array('id' => '15003'),
            array('id' => '2747'),
            array('id' => '36082'),
            array('id' => '15329'),
            array('id' => '36091'),
            array('id' => '36144'),
            array('id' => '35892'),
            array('id' => '36133'),
            array('id' => '17504'),
            array('id' => '36109'),
            array('id' => '36162'),
            array('id' => '36106'),
            array('id' => '44299'),
            array('id' => '36056'),
            array('id' => '34518'),
            array('id' => '19254')
        );
        $ids = [];
        foreach ($wisdomsIds as $wisId) {
            $ids[] = (int)$wisId['id'];
        }
        foreach ($ids as $id) {
            $wisdom = Wisdom::where("id", "=", $id);
            $wisdom->delete();
        }
        return "done";
        $wisdoms = Wisdom::inRandomOrder()->paginate(7);
        if (request()->ajax()) {
            return $this->ajax($wisdoms);
        }
        return view('home')->with(compact('wisdoms'));
    }
    public function getOneWisdom(Wisdom $wisdom)
    {
        return $wisdom->text;
    }
    public function getWisdomsForCategory(int $originalId)
    {
        $id = '%' . $originalId . '%';
        $wisdoms = Wisdom::where('ids', 'LIKE', $id)->inRandomOrder()->paginate(9);
        if (request()->ajax()) {
            return $this->ajax($wisdoms);
        }
        return view('home')->with(compact('wisdoms'))->with(compact('originalId'));
    }
    public function searchForWisdom()
    {
        $id = (int)request()->q;
        if ($id) {
            $wisdoms = Wisdom::where('id', '=', $id)->paginate(9);;
        } else {
            $q = '%' . request()->q . '%';
            $newSearchText = request()->q;
            if (strpos(request()->q, "ه")) {
                $newSearchText = str_replace('ه', '(ة|ه)', $newSearchText);
            } elseif (strpos(request()->q, "ة")) {
                $newSearchText = str_replace('ة', '(ة|ه)', $newSearchText);
            } elseif (strpos(request()->q, "ا")) {
                $newSearchText = str_replace('ا', '(إ|أ|ا|آ|ء|ئ|ؤ)', $newSearchText);
            } elseif (strpos(request()->q, "أ")) {
                $newSearchText = str_replace('أ', '(إ|أ|ا|آ|ء|ئ|ؤ)', $newSearchText);
            } elseif (strpos(request()->q, "إ")) {
                $newSearchText = str_replace('إ', '(إ|أ|ا|آ|ء|ئ)', $newSearchText);
            } elseif (strpos(request()->q, "آ")) {
                $newSearchText = str_replace('آ', '(إ|أ|ا|آ|ء)', $newSearchText);
            } elseif (strpos(request()->q, "ء")) {
                $newSearchText = str_replace('ء', '(أ|ا|آ|ء|ئ|ؤ)', $newSearchText);
            } elseif (strpos(request()->q, "و")) {
                $newSearchText = str_replace('و', '(و|ؤ)', $newSearchText);
            } elseif (strpos(request()->q, "ي")) {
                $newSearchText = str_replace('ي', '(ئ|ي|ى)', $newSearchText);
            } elseif (strpos(request()->q, "ى")) {
                $newSearchText = str_replace('ى', '(ئ|ي|ى)', $newSearchText);
            } elseif (strpos(request()->q, "ئ")) {
                $newSearchText = str_replace('ئ', '(ئ|ي|ى)', $newSearchText);
            }

            $regular_spaces = str_replace(' ', "\xc2\xa0", request()->q);
            if (strpos(request()->q, "ه")) {
                $regular_spaces = str_replace('ه', '(ة|ه)', $regular_spaces);
            } elseif (strpos(request()->q, "ة")) {
                $regular_spaces = str_replace('ة', '(ة|ه)', $regular_spaces);
            } elseif (strpos(request()->q, "ا")) {
                $regular_spaces = str_replace('ا', '(إ|أ|ا|آ|ء|ئ|ؤ)', $regular_spaces);
            } elseif (strpos(request()->q, "أ")) {
                $regular_spaces = str_replace('أ', '(إ|أ|ا|آ|ء|ئ|ؤ)', $regular_spaces);
            } elseif (strpos(request()->q, "إ")) {
                $regular_spaces = str_replace('إ', '(إ|أ|ا|آ|ء|ئ)', $regular_spaces);
            } elseif (strpos(request()->q, "آ")) {
                $regular_spaces = str_replace('آ', '(إ|أ|ا|آ|ء)', $regular_spaces);
            } elseif (strpos(request()->q, "ء")) {
                $regular_spaces = str_replace('ء', '(أ|ا|آ|ء|ئ|ؤ)', $regular_spaces);
            } elseif (strpos(request()->q, "و")) {
                $regular_spaces = str_replace('و', '(و|ؤ)', $regular_spaces);
            } elseif (strpos(request()->q, "ي")) {
                $regular_spaces = str_replace('ي', '(ئ|ي|ى)', $regular_spaces);
            } elseif (strpos(request()->q, "ى")) {
                $regular_spaces = str_replace('ى', '(ئ|ي|ى)', $regular_spaces);
            } elseif (strpos(request()->q, "ئ")) {
                $regular_spaces = str_replace('ئ', '(ئ|ي|ى)', $regular_spaces);
            }
            $newSearchText2 = $regular_spaces;
            $wisdoms =
                Wisdom::where("search_text", "LIKE", $q)
                ->orWhere("search_text", "REGEXP", $newSearchText)
                ->orWhere("search_text", "REGEXP", $newSearchText2)
                ->where("text", "LIKE", $q)
                ->orWhere("text", "REGEXP", $newSearchText)
                ->orWhere("text", "REGEXP", $newSearchText2)
                ->paginate(9);
        }
        if (request()->ajax()) {
            return $this->ajax($wisdoms);
        }
        return view('home')->with(compact('wisdoms'))->with('q', request()->q);
    }

    public function changeCategory()
    {
        $wisdom = Wisdom::where("id", "=", request()->wisdomId)->first();
        $wisdom->ids = request()->newCategories;
        if ($wisdom->save()) {
            $result['error'] = false;
            return json_encode($result);
        } else {
            $result['error'] = true;
            return json_encode($result);
        }
    }
    public function changeText()
    {
        $wisdom = Wisdom::where("id", "=", request()->wisdomId)->first();
        $wisdom->text = request()->text;
        if ($wisdom->save()) {
            $result['error'] = false;
            return back()->with("success", "done");
        } else {
            $result['error'] = true;
            $wisdoms = Wisdom::where("id", "=", request()->wisdomId)->get();
            return view('home')->with(compact('wisdoms'))->with("error", "fail");
        }
    }
    public function deleteWisdom(Wisdom $wisdom)
    {
        $wisdom->delete();
        $result['error'] = false;
        return back();
    }
    public function getRandomQuote()
    {
        $headers = apache_request_headers();
        $token = $headers['Token'];
        $rapidApi = $headers['X-RapidAPI-Proxy-Secret'] === "4e81b800-7e6a-11ec-bd3d-d70ef1ec455f";
        $response = array();
        $path = public_path() . '/json/categories.json';
        $file = file_get_contents($path);
        $categories = json_decode($file, true);
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && $token === "ABJEDHOWS" && $rapidApi) {
            $response['status'] = 200;
            $wisdom = Wisdom::inRandomOrder()->first();
            $responseWisdom['id'] = $wisdom->id;
            $responseWisdom['text'] = nl2br($wisdom->text . "\n\n" . "د. عبدالعزيز فيصل المطوع");
            $responseWisdom['categories'] = [];
            foreach (json_decode($wisdom->ids) as $id) {
                $responseWisdom['categories'][] = $categories[$id];
            }
            $response['wisdom'] = $responseWisdom;
        } else {
            $response['status'] = 400;
            $response['message'] = "Invalid Request";
        }
        return $response;
    }
    public function createWisdoms()
    {
        $texts = explode("||", request()->wisdoms);
        $wisdoms = [];
        foreach ($texts as $text) {
            $wisdom = new Wisdom();
            $wisdom->text = $text;
            $wisdom->ids = json_encode(["1467"]);
            $wisdom->save();
            $wisdoms[] = $wisdom;
        }
        return view('home')->with(compact('wisdoms'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreWisdomRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWisdomRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wisdom  $wisdom
     * @return \Illuminate\Http\Response
     */
    public function show(Wisdom $wisdom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wisdom  $wisdom
     * @return \Illuminate\Http\Response
     */
    public function edit(Wisdom $wisdom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWisdomRequest  $request
     * @param  \App\Models\Wisdom  $wisdom
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWisdomRequest $request, Wisdom $wisdom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wisdom  $wisdom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wisdom $wisdom)
    {
        //
    }
}
