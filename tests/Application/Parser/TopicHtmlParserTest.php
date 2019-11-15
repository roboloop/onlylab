<?php

namespace App\Tests\Application\Parser;

use App\Application\Dto\RawImageDto;
use App\Application\Parser\TopicHtmlParser;
use PHPUnit\Framework\TestCase;

class TopicHtmlParserTest extends TestCase
{
    /**
     * @dataProvider data
     */
    public function testParse($content, $exId, $forumExId, $forumTitle, $rawTitle, $size, $exCreatedAt, $images)
    {
        $rawTopic = (new TopicHtmlParser)->parse($content);

        $totalImages    = count($images);
        $totalRawImages = count($rawTopic->getImages());
        $this->assertEquals($exId, $rawTopic->getExId());
        $this->assertEquals($forumExId, $rawTopic->getForumExId());
        $this->assertEquals($forumTitle, $rawTopic->getForumTitle());
        $this->assertEquals($rawTitle, $rawTopic->getRawTitle());
        $this->assertEquals($size, $rawTopic->getSize());
        $this->assertEquals($exCreatedAt, $rawTopic->getExCreatedAt());
        $this->assertEquals($totalImages, $totalRawImages);

        /** @var \App\Application\Dto\RawImageDto[] $rawImages */
        $rawImages = $rawTopic->getImages();
        for ($i = 0; $i < $totalImages; $i++) {
            list($frontUrl, $reference, $place, $spoilerName) = $images[$i];
            $this->assertEquals($frontUrl, $rawImages[$i]->getFrontUrl());
            $this->assertEquals($reference, $rawImages[$i]->getReference());
            $this->assertEquals($place, $rawImages[$i]->getPlace());
            $this->assertEquals($spoilerName, $rawImages[$i]->getSpoilerName());
        }
    }

    public function data()
    {
        return [
            [
                file_get_contents('mdwaylo2.ftmf'),
                '5223361',
                '2007',
                'tgtuuoea 6486 (gf biaaw) / nepjzkh\'h 6486 (gf biaaw)',
                '[kxml.zzs / ypndryqkkwoyz.zzs] idmh flmh - j lecw pd moov fonv [6486.73.94, lcd feh, kdoqxmt, apomiau, zhyk ixa, weke hmyr, smh wy owqvq, etpfp, hlpt ixa, dgffhq, mdqlt pwmlc, bdge, cmj, dpis zui, bstyfrz, 0614g]',
                null,
                null,
                [
                    [
                        'ezavi://q474.booaowy.fw/pha/6486/0965/u1/h8m8c92dnztbc6f494265u6c65lo6de1.wze',
                        null,
                        RawImageDto::PLACE_ON_PAGE,
                        null,
                    ],
                    [
                        'ezavi://q474.booaowy.fw/wsquz/6486/0965/13/_6ono999u569f96946u431u341dx36c13.fiug',
                        'ezavi://booaowy.fw/ydzj/474/6486/0965/_6ono999u569f96946u431u341dx36c13.wze.ftmf',
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'Примеры',
                    ],
                    [
                        'ezavi://q474.booaowy.fw/wsquz/6486/0965/8t/_15886793mt4022886hz32fs54488f89t.fiug',
                        'ezavi://booaowy.fw/ydzj/474/6486/0965/_15886793mt4022886hz32fs54488f89t.wze.ftmf',
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'Примеры',
                    ],
                    [
                        'ezavi://q474.booaowy.fw/wsquz/6486/0965/c0/_h44m13m51038f6f1mt6mt63t6m0u2sv0.fiug',
                        'ezavi://booaowy.fw/ydzj/474/6486/0965/_h44m13m51038f6f1mt6mt63t6m0u2sv0.wze.ftmf',
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'Примеры',
                    ],
                    [
                        'ezavi://q474.booaowy.fw/wsquz/6486/0965/tr/_h6fbdm08m52opu0t449fs1688nwzk6tr.fiug',
                        'ezavi://booaowy.fw/ydzj/474/6486/0965/_h6fbdm08m52opu0t449fs1688nwzk6tr.wze.ftmf',
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'Примеры',
                    ],
                    [
                        'ezavi://q474.booaowy.fw/wsquz/6486/0965/71/_083dx4f536c0qd48933h1h8tgr668820.fiug',
                        'ezavi://booaowy.fw/ydzj/474/6486/0965/_083dx4f536c0qd48933h1h8tgr668820.wze.ftmf',
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'Скринлист',
                    ],
                    [
                        'ezavi://h4t6.rxqmrvyb.hct/xg/4h1017ki07f8c0xkje3m2m1228u71c89/6049090.erc',
                        null,
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'GIF preview',
                    ],
                ],
            ],
            [
                file_get_contents('mdwaylo6.ftmf'),
                '8129309',
                '2007',
                'tgtuuoea 6486 (gf biaaw) / nepjzkh\'h 6486 (gf biaaw)',
                '[sblcnlxerhtk.zzs / zuqluub.zzs] jet ndr qfkoq - cvs\'f kva jet jx [6486.73.94, lcd feh, smh qyuu, docwjg, lffk, xar, ohjjvqc, gaha nennds, shwldsc, dubdafxr, oewbom, abhqpf, trvgk, 081g]',
                null,
                null,
                [
                    [
                        'ezavi://q474.booaowy.fw/pha/6486/0965/73/f16u4fmg2c37431h6eh1t544c2336f73.wze',
                        null,
                        RawImageDto::PLACE_ON_PAGE,
                        null,
                    ],
                    [
                        'ezavi://q474.booaowy.fw/wsquz/6486/0965/16/085t7652f920h6t0xr2u8c195u317902.fiug',
                        'ezavi://booaowy.fw/ydzj/474/6486/0965/085t7652f920h6t0xr2u8c195u317902.wze.ftmf',
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'Примеры',
                    ],
                    [
                        'ezavi://q474.booaowy.fw/wsquz/6486/0965/16/55mbuj022fgns0by4tp93477cuy8cb16.fiug',
                        'ezavi://booaowy.fw/ydzj/474/6486/0965/55mbuj022fgns0by4tp93477cuy8cb16.wze.ftmf',
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'Примеры',
                    ],
                    [
                        'ezavi://q474.booaowy.fw/wsquz/6486/0965/10/2c0qz4f430sjnl8113f0t0h1jp6aro10.fiug',
                        'ezavi://booaowy.fw/ydzj/474/6486/0965/2c0qz4f430sjnl8113f0t0h1jp6aro10.wze.ftmf',
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'Примеры',
                    ],
                    [
                        'ezavi://q474.booaowy.fw/wsquz/6486/0965/ad/3h4c508qd4ts6m2991f732m72u0848ad.fiug',
                        'ezavi://booaowy.fw/ydzj/474/6486/0965/3h4c508qd4ts6m2991f732m72u0848ad.wze.ftmf',
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'Примеры',
                    ],
                    [
                        'ezavi://q474.booaowy.fw/wsquz/6486/0965/c8/_6qz2271tr16t16f225tr4440468c36c8.fiug',
                        'ezavi://booaowy.fw/ydzj/474/6486/0965/_6qz2271tr16t16f225tr4440468c36c8.wze.ftmf',
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'Скринлист',
                    ],
                ],
            ],
            [
                file_get_contents('mdwaylo1.ftmf'),
                '9689596',
                '2007',
                'tgtuuoea 6486 (gf biaaw) / nepjzkh\'h 6486 (gf biaaw)',
                '[ngpopjlkdvrqto.zzs / kpttvzhr.zzs] mhufyca xhfcq - zvcgr lg vgu vrspvu: atmy 2 (94.73.6486) [6486 l., kfjlzgii, kpj pwan, kpj pwan rkdrvji, ohjjvqc (cmj), ydpjdusl, wohzpnru, rikobmum, lffk, sygbhz, qwyrwo kqe, ejldw pwmlc, hasgvnl pwmlc, aszm ldimumfav, 0614g]',
                null,
                null,
                [
                    [
                        'ezavi://q474.booaowy.fw/pha/6486/0965/8h/2u06h36tm4h53hgf3u524jbwcq97658h.wze',
                        null,
                        RawImageDto::PLACE_ON_PAGE,
                        null,
                    ],
                    [
                        'ezavi://q474.booaowy.fw/pha/6486/0965/6m/by0qz0361f6511ww9031m1c18h1c390m.mxv',
                        null,
                        RawImageDto::PLACE_ON_PAGE,
                        null,
                    ],
                    [
                        'ezavi://q675.booaowy.fw/pha/6486/4314/c2/f3t53wi6f43849f13592h09113c593c2.mxv',
                        null,
                        RawImageDto::PLACE_ON_PAGE,
                        null,
                    ],
                    [
                        'ezavi://q474.booaowy.fw/wsquz/6486/0965/48/_87u4xr41c50weuz65mt1388443h15275.fiug',
                        'ezavi://booaowy.fw/ydzj/474/6486/0965/_87u4xr41c50weuz65mt1388443h15275.wze.ftmf',
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'Скриншоты',
                    ],
                    [
                        'ezavi://q474.booaowy.fw/wsquz/6486/0965/t0/_8mt3m1925u48916u8m89ts63c4ox2hz0.fiug',
                        'ezavi://booaowy.fw/ydzj/474/6486/0965/_8mt3m1925u48916u8m89ts63c4ox2hz0.wze.ftmf',
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'Скриншоты',
                    ],
                    [
                        'ezavi://q474.booaowy.fw/wsquz/6486/0965/49/_431f98h67m6tm1u140m8972055f6c499.fiug',
                        'ezavi://booaowy.fw/ydzj/474/6486/0965/_431f98h67m6tm1u140m8972055f6c499.wze.ftmf',
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'Скриншоты',
                    ],
                    [
                        'ezavi://q474.booaowy.fw/wsquz/6486/0965/60/_07c9283c78852c41fvj1t47ge2jw8c60.fiug',
                        'ezavi://booaowy.fw/ydzj/474/6486/0965/_07c9283c78852c41fvj1t47ge2jw8c60.wze.ftmf',
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'Скриншоты',
                    ],
                    [
                        'ezavi://q474.booaowy.fw/wsquz/6486/0965/64/_0518u1ad1h780oo6f352f48t09768098.fiug',
                        'ezavi://booaowy.fw/ydzj/474/6486/0965/_0518u1ad1h780oo6f352f48t09768098.wze.ftmf',
                        RawImageDto::PLACE_UNDER_SPOILER,
                        'Скринлист',
                    ],
                ],
            ],
        ];
    }
}
