<?php

namespace App\Tests\Application\Parser\ForumHtmlParser;

use App\Application\Parser\ForumHtmlParser;
use PHPUnit\Framework\TestCase;

class ForumHtmlParserTest extends TestCase
{
    /**
     * @dataProvider data
     */
    public function testParseTopicsHeaders($content, $forumExId, $forumTitle, $topics)
    {
        $rawTopics = (new ForumHtmlParser)->parseTopicsHeaders($content);

        foreach ($rawTopics as $rawTopic) {
            list($exId, $rawTitle, $size, $exCreatedAt) = $topics;
            $this->assertEquals($forumExId, $rawTopic->getForum()->getExId());
            $this->assertEquals($forumTitle, $rawTopic->getForum()->getTitle());
            $this->assertEquals($exId, $rawTopic->getExId());
            $this->assertEquals($rawTitle, $rawTopic->getRawTitle());
            $this->assertEquals($size, $rawTopic->getSize());
            $this->assertEquals($exCreatedAt, $rawTopic->getExCreatedAt());
        }
    }

    public function data()
    {
        return [
            [
                file_get_contents('mdwaylo2.ftmf'),
                '2007',
                'tgtuuoea 6486 (gf biaaw) / nepjzkh\'h 6486 (gf biaaw)',
                [
                    '6470510',
                    '[zskopi.hm / zskopi.yc] jgihlmf p. (94) & ojrc (21) - xjzygxdx lswfq 98 [6486-73-95, baoujym, nqt & tsgfu emvxatoe, zmuyxs, fnoohek pfin, 0614g]',
                    '6.32 ll',
                    '6486-73-60 31:65',
                ],
                [
                    '7286724',
                    '[uswxij.zzs] nkiamq brl - zzf fuif tmos g ubqd (04.73.6486) [6486 l., ydpjdusl, ohjjvqc, pwmlc jieleka, qqszskwxjw, wazfizmjyq, vgurff, glfpyvjq, uathwodv, mqbn, mdjwi, 0614g]',
                    '1.44 ll',
                    '6486-73-60 31:48',
                ],
                [
                    '7286724',
                    '[lzwmjlwrtzdlxuax.zzs] mwvupl voqf ([lzwmjlwrtzdlxuax] mwvupl voqf - mwvupl w\'h phxan injxtisgk mlmon) [lzwmjlwrtzdlxuax] mwvupl voqf - mwvupl w\'h phxan injxtisgk mlmon [6486-73-04, zhyk mtqq, gfwieqfsa, smh qm khjby, smh ofespjt, rrycfdfqqv, phxan lecw, 0614g]',
                    '1.26 ll',
                    '6486-73-60 26:56',
                ],
            ],
            [
                file_get_contents('mdwaylo6.ftmf'),
                '7965',
                'yjwsrnq x jvfrx / plpnjau & abknh',
                [
                    '3064303',
                    '[sqofvydn.zzs] tne yktd / uzot 65 xrsre [asmy, vxv, pjzz, dijl abknh, plpnjau, rcrl vmcqkin, snjkkn, whslhsw. 1941g, 0614g, wharaq]',
                    '19.10 ll',
                    '6486-09-66 65:72',
                ],
                [
                    '5048127',
                    '[annwufylgzs.zzs] / tpkhjsu yjwsrnq (79 xuirzud) [7980-9579 fr., lcd vvp,duvg,eywi,emvxatoe,eywi zib kryegoy dmrvhas,vqjdg, lmnew,081g]',
                    '89.48 ll',
                    '2647-26-94 89:67',
                ],
                [
                    '5155647',
                    '[eeuyhwzkhaofdh.zzs / ekis.zzs] sxenbs wcdql, pjd talgzmxq - xwytmab\'h wsv: sxenbs wcdql fcykb aqixhki me xwytmab pjd talgzmxq (04.73.6486) [6486 l., baoujym, obboe-me, asmy, vazobytj, omdu, asmy becx, asmy plpnjau, abknh, kqe jieleka, yyrhmwb, meshrfrevwnq, lefpqggt, uyqahuqqw, nepjzkh, 081g]',
                    '6.56 ll',
                    '6486-73-60 64:41',
                ],
                [
                    '3853889',
                    '[uaefesjaaa.zzs] kpj eywi ityh tvmhla94 6486-73-48 [6486 l., asmy, abknh, xpinqwv plpdkbw, meshrfrevwnq, xhit, omdu, iwjnjz 0614g]',
                    '94.98 ll',
                    '6486-73-04 89:23',
                ],
            ],
            [
                file_get_contents('mdwaylo1.ftmf'),
                '4590',
                'dmkmnl: ufxhyqgn ozpmnfwwr / mkxuem: fgge kwtixbikjz',
                [
                    '7512232',
                    'hfjm pwyh bnr mlrciqqq tlnxdnfd (odjm, gabqv) (ce. 2-1 sr 6 + emtup 2-6) [lrq] [6486, nlwwgal, ebo, zwo, pha clxebu, fgcx, duvg, eywi, obaf, hsuhel, qarcr, ciexrxvs, rtxhya] [xcb / nvi (2-1)/ ntf (2-6)]',
                    '491 jr',
                    '6486-73-73 94:64',
                ],
                [
                    '6237975',
                    'krmnwxv nx mzlq (odjm) (ce. 2-6 sr ?) [lrq] [6486, nlwwgal, pha clxebu, duvg, eywi, obaf, hsuhel, qarcr, ciexrxvs, rtxhya] [xcb / nvi (2-6)/ ntf (2)/ jiv (2-6)]',
                    '391 jr',
                    '6486-73-04 94:31',
                ],
            ]
        ];
    }
}
