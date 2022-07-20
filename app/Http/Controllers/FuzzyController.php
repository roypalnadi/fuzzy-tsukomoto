<?php

namespace App\Http\Controllers;

class FuzzyController extends Controller
{
    private const PERSAMAAN_KURANG_DARI = 1;
    private const PERSAMAAN_LEBIH_DARI = 2;
    private const PERSAMAAN_SEGITIGA = 3;

    private const LULUS_BAKOMSUS = 1;
    private const TIDAK_LULUS = 2;
    private const LULUS = 3;

    private $nilaiPsikologiMin = 0;
    private $nilaiPsikologiMid = 0;
    private $nilaiPsikologiMax = 0;
    private $nilaiTkkMin = 0;
    private $nilaiTkkMid = 0;
    private $nilaiTkkMax = 0;
    private $nilaiJasmaniMin = 0;
    private $nilaiJasmaniMid = 0;
    private $nilaiJasmaniMax = 0;
    private $nilaiAkademikMin = 0;
    private $nilaiAkademikMid = 0;
    private $nilaiAkademikMax = 0;
    private $nilaiKuotaMin = 0;
    private $nilaiKuotaMax = 0;

    private $arrayPsikologi = [];
    private $arrayTkk = [];
    private $arrayJasmani = [];
    private $arrayAkademik = [];
    private $arrayKuota = [];

    private $psikologi = 0;
    private $tkk = 0;
    private $jasmani = 0;
    private $akademik = 0;
    private $kuota = 0;

    private $a = [];
    private $z = [];

    private $hasil = 0;

    public function __construct($psikologi, $jasmani, $akademik)
    {
        $this->psikologi = $psikologi;
        // $this->tkk = $tkk;
        $this->jasmani = $jasmani;
        $this->akademik = $akademik;
        // $this->kuota = $kuota;
    }

    public static function init($psikologi, $jasmani, $akademik)
    {
        $proses = new self($psikologi, $jasmani, $akademik);
        $proses->prepare();
        $proses->fungsiKeanggotaan();
        $proses->rule();
        $proses->defuzzifikasi();

        return $proses->hasil;
    }

    private function prepare()
    {
        $this->nilaiPsikologiMin = 61;
        $this->nilaiPsikologiMid = 70;
        $this->nilaiPsikologiMax = 79;

        $this->nilaiTkkMin = 53;
        $this->nilaiTkkMid = 58;
        $this->nilaiTkkMax = 63;

        $this->nilaiJasmaniMin = 64;
        $this->nilaiJasmaniMid = 75;
        $this->nilaiJasmaniMax = 86;

        $this->nilaiAkademikMin = 42;
        $this->nilaiAkademikMid = 54;
        $this->nilaiAkademikMax = 66;

        $this->nilaiKuotaMin = 1;
        $this->nilaiKuotaMax = 46;

        // $this->psikologi = 70;
        // $this->tkk = 61.45;
        // $this->jasmani = 73.43;
        // $this->akademik = 0;
        // $this->kuota = 1;
    }

    public function fungsiKeanggotaan()
    {
        $nilaiPsikologCukup = self::persamaan(self::PERSAMAAN_KURANG_DARI, $this->psikologi, $this->nilaiPsikologiMin, $this->nilaiPsikologiMid);
        $nilaiPsikologBaik = self::persamaan(self::PERSAMAAN_SEGITIGA, $this->psikologi, $this->nilaiPsikologiMin, $this->nilaiPsikologiMax, $this->nilaiPsikologiMid);
        $nilaiPsikologSangatBaik = self::persamaan(self::PERSAMAAN_LEBIH_DARI, $this->psikologi, $this->nilaiPsikologiMid, $this->nilaiPsikologiMax);

        // $nilaiTkkCukup = self::persamaan(self::PERSAMAAN_KURANG_DARI, $this->tkk, $this->nilaiTkkMin, $this->nilaiTkkMid);
        // $nilaiTkkBaik = self::persamaan(self::PERSAMAAN_SEGITIGA, $this->tkk, $this->nilaiTkkMin, $this->nilaiTkkMax, $this->nilaiTkkMid);
        // $nilaiTkkSangatBaik = self::persamaan(self::PERSAMAAN_LEBIH_DARI, $this->tkk, $this->nilaiTkkMid, $this->nilaiTkkMax);

        $nilaiJasmaniCukup = self::persamaan(self::PERSAMAAN_KURANG_DARI, $this->jasmani, $this->nilaiJasmaniMin, $this->nilaiJasmaniMid);
        $nilaiJasmaniBaik = self::persamaan(self::PERSAMAAN_SEGITIGA, $this->jasmani, $this->nilaiJasmaniMin, $this->nilaiJasmaniMax, $this->nilaiJasmaniMid);
        $nilaiJasmaniSangatBaik = self::persamaan(self::PERSAMAAN_LEBIH_DARI, $this->jasmani, $this->nilaiJasmaniMid, $this->nilaiJasmaniMax);

        $nilaiAkademikCukup = self::persamaan(self::PERSAMAAN_KURANG_DARI, $this->akademik, $this->nilaiAkademikMin, $this->nilaiAkademikMid);
        $nilaiAkademikBaik = self::persamaan(self::PERSAMAAN_SEGITIGA, $this->akademik, $this->nilaiAkademikMin, $this->nilaiAkademikMax, $this->nilaiAkademikMid);
        $nilaiAkademikSangatBaik = self::persamaan(self::PERSAMAAN_LEBIH_DARI, $this->akademik, $this->nilaiAkademikMid, $this->nilaiAkademikMax);

        // $nilaiKuotaTms = self::persamaan(self::PERSAMAAN_KURANG_DARI, $this->kuota, $this->nilaiKuotaMin, $this->nilaiKuotaMax);
        // $nilaiKuotaMs = self::persamaan(self::PERSAMAAN_LEBIH_DARI, $this->kuota, $this->nilaiKuotaMin, $this->nilaiKuotaMax);

        $this->arrayPsikologi = [
            'cukup' => $nilaiPsikologCukup,
            'baik' => $nilaiPsikologBaik,
            'sangat_baik' => $nilaiPsikologSangatBaik,
        ];

        // $this->arrayTkk = [
        //     'cukup' => $nilaiTkkCukup,
        //     'baik' => $nilaiTkkBaik,
        //     'sangat_baik' => $nilaiTkkSangatBaik,
        // ];

        $this->arrayJasmani = [
            'cukup' => $nilaiJasmaniCukup,
            'baik' => $nilaiJasmaniBaik,
            'sangat_baik' => $nilaiJasmaniSangatBaik,
        ];

        $this->arrayAkademik = [
            'cukup' => $nilaiAkademikCukup,
            'baik' => $nilaiAkademikBaik,
            'sangat_baik' => $nilaiAkademikSangatBaik,
        ];

        // $this->arrayKuota = [
        //     'tms' => $nilaiKuotaTms,
        //     'ms' => $nilaiKuotaMs,
        // ];
    }

    public function rule()
    {
        $psikologi = $this->arrayPsikologi;
        // $tkk = $this->arrayTkk;
        $jasmani = $this->arrayJasmani;
        $akademik = $this->arrayAkademik;
        // $kuota = $this->arrayKuota;

        $a1 = min($psikologi['sangat_baik'], $jasmani['sangat_baik'], $akademik['sangat_baik']);
        $z1 = self::mencariZ(self::LULUS, $a1);

        $a2 = min($psikologi['sangat_baik'], $jasmani['sangat_baik'], $akademik['baik']);
        $z2 = self::mencariZ(self::LULUS, $a2);

        $a3 = min($psikologi['sangat_baik'], $jasmani['sangat_baik'], $akademik['cukup']);
        $z3 = self::mencariZ(self::TIDAK_LULUS, $a3);

        $a4 = min($psikologi['sangat_baik'], $jasmani['baik'], $akademik['sangat_baik']);
        $z4 = self::mencariZ(self::LULUS, $a4);

        $a5 = min($psikologi['sangat_baik'], $jasmani['baik'], $akademik['baik']);
        $z5 = self::mencariZ(self::LULUS, $a5);

        $a6 = min($psikologi['sangat_baik'], $jasmani['baik'], $akademik['cukup']);
        $z6 = self::mencariZ(self::TIDAK_LULUS, $a6);

        $a7 = min($psikologi['sangat_baik'], $jasmani['cukup'], $akademik['sangat_baik']);
        $z7 = self::mencariZ(self::LULUS, $a7);

        $a8 = min($psikologi['sangat_baik'], $jasmani['cukup'], $akademik['baik']);
        $z8 = self::mencariZ(self::LULUS, $a8);

        $a9 = min($psikologi['sangat_baik'], $jasmani['cukup'], $akademik['cukup']);
        $z9 = self::mencariZ(self::TIDAK_LULUS, $a9);

        $a10 = min($psikologi['baik'], $jasmani['sangat_baik'], $akademik['sangat_baik']);
        $z10 = self::mencariZ(self::LULUS, $a10);

        $a11 = min($psikologi['baik'], $jasmani['sangat_baik'], $akademik['baik']);
        $z11 = self::mencariZ(self::TIDAK_LULUS, $a11);

        $a12 = min($psikologi['baik'], $jasmani['sangat_baik'], $akademik['cukup']);
        $z12 = self::mencariZ(self::TIDAK_LULUS, $a12);

        $a13 = min($psikologi['baik'], $jasmani['baik'], $akademik['sangat_baik']);
        $z13 = self::mencariZ(self::LULUS, $a13);

        $a14 = min($psikologi['baik'], $jasmani['baik'], $akademik['baik']);
        $z14 = self::mencariZ(self::TIDAK_LULUS, $a14);

        $a15 = min($psikologi['baik'], $jasmani['baik'], $akademik['cukup']);
        $z15 = self::mencariZ(self::TIDAK_LULUS, $a15);

        $a16 = min($psikologi['baik'], $jasmani['cukup'], $akademik['sangat_baik']);
        $z16 = self::mencariZ(self::LULUS, $a16);

        $a17 = min($psikologi['baik'], $jasmani['cukup'], $akademik['baik']);
        $z17 = self::mencariZ(self::TIDAK_LULUS, $a17);

        $a18 = min($psikologi['baik'], $jasmani['cukup'], $akademik['cukup']);
        $z18 = self::mencariZ(self::TIDAK_LULUS, $a18);

        $a19 = min($psikologi['cukup'], $jasmani['sangat_baik'], $akademik['sangat_baik']);
        $z19 = self::mencariZ(self::TIDAK_LULUS, $a19);

        $a20 = min($psikologi['cukup'], $jasmani['sangat_baik'], $akademik['baik']);
        $z20 = self::mencariZ(self::TIDAK_LULUS, $a20);

        $a21 = min($psikologi['cukup'], $jasmani['sangat_baik'], $akademik['cukup']);
        $z21 = self::mencariZ(self::TIDAK_LULUS, $a21);

        $a22 = min($psikologi['cukup'], $jasmani['baik'], $akademik['sangat_baik']);
        $z22 = self::mencariZ(self::TIDAK_LULUS, $a22);

        $a23 = min($psikologi['cukup'], $jasmani['baik'], $akademik['baik']);
        $z23 = self::mencariZ(self::TIDAK_LULUS, $a23);

        $a24 = min($psikologi['cukup'], $jasmani['baik'], $akademik['cukup']);
        $z24 = self::mencariZ(self::TIDAK_LULUS, $a24);

        $a25 = min($psikologi['cukup'], $jasmani['cukup'], $akademik['sangat_baik']);
        $z25 = self::mencariZ(self::TIDAK_LULUS, $a25);

        $a26 = min($psikologi['cukup'], $jasmani['cukup'], $akademik['baik']);
        $z26 = self::mencariZ(self::TIDAK_LULUS, $a26);

        $a27 = min($psikologi['cukup'], $jasmani['cukup'], $akademik['cukup']);
        $z27 = self::mencariZ(self::TIDAK_LULUS, $a27);

        // $a1 = min($psikologi['cukup'], $tkk['baik'], $jasmani['cukup'], $kuota['ms']);
        // $z1 = self::mencariZ(self::LULUS_BAKOMSUS, $a1);

        // $a2 = min($psikologi['baik'], $tkk['baik'], $jasmani['cukup'], $kuota['ms']);
        // $z2 = self::mencariZ(self::LULUS_BAKOMSUS, $a2);

        // $a3 = min($psikologi['baik'], $tkk['sangat_baik'], $jasmani['cukup'], $kuota['ms']);
        // $z3 = self::mencariZ(self::LULUS_BAKOMSUS, $a3);

        // $a4 = min($psikologi['sangat_baik'], $tkk['sangat_baik'], $jasmani['baik'], $kuota['ms']);
        // $z4 = self::mencariZ(self::LULUS_BAKOMSUS, $a4);

        // $a5 = min($psikologi['sangat_baik'], $tkk['baik'], $jasmani['sangat_baik'], $kuota['ms']);
        // $z5 = self::mencariZ(self::LULUS_BAKOMSUS, $a5);

        // $a6 = min($psikologi['sangat_baik'], $tkk['baik'], $jasmani['cukup'], $kuota['ms']);
        // $z6 = self::mencariZ(self::LULUS_BAKOMSUS, $a6);

        // $a7 = min($psikologi['baik'], $akademik['baik'], $jasmani['sangat_baik'], $kuota['ms']);
        // $z7 = self::mencariZ(self::LULUS_PTU, $a7);

        // $a8 = min($psikologi['sangat_baik'], $akademik['baik'], $jasmani['sangat_baik'], $kuota['ms']);
        // $z8 = self::mencariZ(self::LULUS_PTU, $a8);

        // $a9 = min($psikologi['baik'], $akademik['sangat_baik'], $jasmani['baik'], $kuota['ms']);
        // $z9 = self::mencariZ(self::LULUS_PTU, $a9);

        // $a10 = min($psikologi['baik'], $akademik['sangat_baik'], $jasmani['sangat_baik'], $kuota['ms']);
        // $z10 = self::mencariZ(self::LULUS_PTU, $a10);

        // $a11 = min($psikologi['sangat_baik'], $akademik['sangat_baik'], $jasmani['baik'], $kuota['ms']);
        // $z11 = self::mencariZ(self::LULUS_PTU, $a11);

        // $a12 = min($psikologi['cukup'], $akademik['baik'], $jasmani['baik'], $kuota['ms']);
        // $z12 = self::mencariZ(self::LULUS_PTU, $a12);

        // $a13 = min($psikologi['baik'], $akademik['cukup'], $jasmani['sangat_baik'], $kuota['ms']);
        // $z13 = self::mencariZ(self::LULUS_PTU, $a13);

        // $a14 = min($psikologi['baik'], $akademik['sangat_baik'], $jasmani['cukup'], $kuota['ms']);
        // $z14 = self::mencariZ(self::LULUS_PTU, $a14);

        // $a15 = min($psikologi['sangat_baik'], $akademik['cukup'], $jasmani['sangat_baik'], $kuota['ms']);
        // $z15 = self::mencariZ(self::LULUS_PTU, $a15);

        // $a16 = min($psikologi['sangat_baik'], $akademik['cukup'], $jasmani['baik'], $kuota['ms']);
        // $z16 = self::mencariZ(self::LULUS_PTU, $a16);

        // $a17 = min($psikologi['cukup'], $akademik['sangat_baik'], $jasmani['sangat_baik'], $kuota['ms']);
        // $z17 = self::mencariZ(self::LULUS_PTU, $a17);

        // $a18 = min($psikologi['sangat_baik'], $akademik['cukup'], $jasmani['baik'], $kuota['ms']);
        // $z18 = self::mencariZ(self::LULUS_PTU, $a18);

        // $a19 = min($psikologi['baik'], $akademik['baik'], $jasmani['baik'], $kuota['ms']);
        // $z19 = self::mencariZ(self::LULUS_PTU, $a19);

        // $a20 = min($psikologi['cukup'], $akademik['cukup'], $jasmani['baik'], $kuota['ms']);
        // $z20 = self::mencariZ(self::LULUS_PTU, $a20);

        // $a21 = min($psikologi['cukup'], $akademik['cukup'], $jasmani['sangat_baik'], $kuota['ms']);
        // $z21 = self::mencariZ(self::LULUS_PTU, $a21);

        // $a22 = min($psikologi['sangat_baik'], $akademik['baik'], $jasmani['cukup'], $kuota['ms']);
        // $z22 = self::mencariZ(self::LULUS_PTU, $a22);

        // $a23 = min($psikologi['sangat_baik'], $akademik['baik'], $jasmani['sangat_baik'], $kuota['ms']);
        // $z23 = self::mencariZ(self::LULUS_PTU, $a23);

        // $a24 = min($psikologi['baik'], $akademik['cukup'], $jasmani['sangat_baik'], $kuota['ms']);
        // $z24 = self::mencariZ(self::LULUS_PTU, $a24);

        // $a25 = min($psikologi['sangat_baik'], $akademik['baik'], $jasmani['baik'], $kuota['tms']);
        // $z25 = self::mencariZ(self::TIDAK_LULUS, $a25);

        // $a26 = min($psikologi['cukup'], $tkk['cukup'], $jasmani['sangat_baik'], $kuota['tms']);
        // $z26 = self::mencariZ(self::TIDAK_LULUS, $a26);

        // $a27 = min($psikologi['baik'], $tkk['cukup'], $jasmani['sangat_baik'], $kuota['tms']);
        // $z27 = self::mencariZ(self::TIDAK_LULUS, $a27);

        // $a28 = min($psikologi['cukup'], $tkk['baik'], $jasmani['baik'], $kuota['tms']);
        // $z28 = self::mencariZ(self::TIDAK_LULUS, $a28);

        // $a29 = min($psikologi['baik'], $akademik['cukup'], $jasmani['baik'], $kuota['tms']);
        // $z29 = self::mencariZ(self::TIDAK_LULUS, $a29);

        // $a30 = min($psikologi['cukup'], $akademik['baik'], $jasmani['baik'], $kuota['tms']);
        // $z30 = self::mencariZ(self::TIDAK_LULUS, $a30);

        // $a31 = min($psikologi['baik'], $akademik['baik'], $jasmani['baik'], $kuota['tms']);
        // $z31 = self::mencariZ(self::TIDAK_LULUS, $a31);

        // $a32 = min($psikologi['baik'], $tkk['cukup'], $jasmani['sangat_baik'], $kuota['tms']);
        // $z32 = self::mencariZ(self::TIDAK_LULUS, $a32);

        // $a33 = min($psikologi['sangat_baik'], $akademik['cukup'], $jasmani['baik'], $kuota['tms']);
        // $z33 = self::mencariZ(self::TIDAK_LULUS, $a33);

        // $a34 = min($psikologi['baik'], $tkk['baik'], $jasmani['sangat_baik'], $kuota['tms']);
        // $z34 = self::mencariZ(self::TIDAK_LULUS, $a34);

        // $a35 = min($psikologi['cukup'], $tkk['cukup'], $jasmani['cukup'], $kuota['tms']);
        // $z35 = self::mencariZ(self::TIDAK_LULUS, $a35);

        // $a36 = min($psikologi['cukup'], $tkk['baik'], $jasmani['sangat_baik'], $kuota['tms']);
        // $z36 = self::mencariZ(self::TIDAK_LULUS, $a36);

        // $a37 = min($psikologi['sangat_baik'], $akademik['baik'], $jasmani['sangat_baik'], $kuota['tms']);
        // $z37 = self::mencariZ(self::TIDAK_LULUS, $a37);

        // $a38 = min($psikologi['baik'], $tkk['baik'], $jasmani['cukup'], $kuota['tms']);
        // $z38 = self::mencariZ(self::TIDAK_LULUS, $a38);

        // $a39 = min($psikologi['cukup'], $akademik['sangat_baik'], $jasmani['cukup'], $kuota['tms']);
        // $z39 = self::mencariZ(self::TIDAK_LULUS, $a39);

        // $a40 = min($psikologi['baik'], $tkk['sangat_baik'], $jasmani['cukup'], $kuota['tms']);
        // $z40 = self::mencariZ(self::TIDAK_LULUS, $a40);

        // $a41 = min($psikologi['sangat_baik'], $tkk['baik'], $jasmani['cukup'], $kuota['tms']);
        // $z41 = self::mencariZ(self::TIDAK_LULUS, $a41);

        // $a42 = min($psikologi['cukup'], $tkk['sangat_baik'], $jasmani['sangat_baik'], $kuota['tms']);
        // $z42 = self::mencariZ(self::TIDAK_LULUS, $a42);

        // $a43 = min($psikologi['sangat_baik'], $tkk['cukup'], $jasmani['cukup'], $kuota['tms']);
        // $z43 = self::mencariZ(self::TIDAK_LULUS, $a43);

        // $a44 = min($psikologi['sangat_baik'], $akademik['sangat_baik'], $jasmani['cukup'], $kuota['ms']);
        // $z44 = self::mencariZ(self::LULUS_PTU, $a44);

        // $a45 = min($psikologi['sangat_baik'], $akademik['sangat_baik'], $jasmani['sangat_baik'], $kuota['ms']);
        // $z45 = self::mencariZ(self::LULUS_PTU, $a45);

        // $a46 = min($psikologi['sangat_baik'], $akademik['baik'], $jasmani['baik'], $kuota['ms']);
        // $z46 = self::mencariZ(self::LULUS_PTU, $a46);

        // $a47 = min($psikologi['cukup'], $akademik['cukup'], $jasmani['sangat_baik'], $kuota['tms']);
        // $z47 = self::mencariZ(self::TIDAK_LULUS, $a47);

        // $a48 = min($psikologi['cukup'], $akademik['cukup'], $jasmani['baik'], $kuota['tms']);
        // $z48 = self::mencariZ(self::TIDAK_LULUS, $a48);

        // $a49 = min($psikologi['sangat_baik'], $akademik['cukup'], $jasmani['baik'], $kuota['tms']);
        // $z49 = self::mencariZ(self::TIDAK_LULUS, $a49);

        // $a50 = min($psikologi['baik'], $akademik['baik'], $jasmani['sangat_baik'], $kuota['tms']);
        // $z50 = self::mencariZ(self::TIDAK_LULUS, $a50);

        // $a51 = min($psikologi['cukup'], $akademik['cukup'], $jasmani['cukup'], $kuota['tms']);
        // $z51 = self::mencariZ(self::TIDAK_LULUS, $a51);

        // $a52 = min($psikologi['cukup'], $akademik['baik'], $jasmani['sangat_baik'], $kuota['tms']);
        // $z52 = self::mencariZ(self::TIDAK_LULUS, $a52);

        // $a53 = min($psikologi['baik'], $akademik['baik'], $jasmani['cukup'], $kuota['tms']);
        // $z53 = self::mencariZ(self::TIDAK_LULUS, $a53);

        // $a54 = min($psikologi['baik'], $akademik['cukup'], $jasmani['cukup'], $kuota['tms']);
        // $z54 = self::mencariZ(self::TIDAK_LULUS, $a54);

        // $a55 = min($psikologi['sangat_baik'], $akademik['baik'], $jasmani['cukup'], $kuota['tms']);
        // $z55 = self::mencariZ(self::TIDAK_LULUS, $a55);

        $this->a = [
            $a1,
            $a2,
            $a3,
            $a4,
            $a5,
            $a6,
            $a7,
            $a8,
            $a9,
            $a10,
            $a11,
            $a12,
            $a13,
            $a14,
            $a15,
            $a16,
            $a17,
            $a18,
            $a19,
            $a20,
            $a21,
            $a22,
            $a23,
            $a24,
            $a25,
            $a26,
            $a27,
        ];

        $this->z = [
            $z1,
            $z2,
            $z3,
            $z4,
            $z5,
            $z6,
            $z7,
            $z8,
            $z9,
            $z10,
            $z11,
            $z12,
            $z13,
            $z14,
            $z15,
            $z16,
            $z17,
            $z18,
            $z19,
            $z20,
            $z21,
            $z22,
            $z23,
            $z24,
            $z25,
            $z26,
            $z27,
        ];
    }

    public function defuzzifikasi()
    {
        $nilai = 0;
        foreach ($this->a as $key => $a) {
            $nilai += ($a * $this->z[$key]);
        }

        $pembagi = array_sum($this->a);

        $hasil = 0;
        if ($pembagi > 0) {
            $hasil = $nilai / $pembagi;
        }

        $this->hasil = $hasil;
    }

    public static function persamaan($type, $value, $min, $max, $mid = null)
    {
        $hasil = 0;
        if ($type == self::PERSAMAAN_KURANG_DARI) {
            if ($value >= $max) {
                $hasil = 0;
            } elseif ($value <= $min) {
                $hasil = 1;
            } else {
                $hasil = ($max - $value) / ($max - $min);
            }
        }

        if ($type == self::PERSAMAAN_LEBIH_DARI) {
            if ($value >= $max) {
                $hasil = 1;
            } elseif ($value <= $min) {
                $hasil = 0;
            } else {
                $hasil = ($value - $min) / ($max - $min);
            }
        }

        if ($type == self::PERSAMAAN_SEGITIGA) {
            if ($value >= $max) {
                $hasil = 0;
            } elseif ($value <= $min) {
                $hasil = 0;
            } elseif ($value >= $min && $value <= $mid) {
                $hasil = ($value - $min) / ($mid - $min);
            } else {
                $hasil = ($max - $value) / ($max - $mid);
            }
        }

        return $hasil;
    }

    public static function mencariZ($type, $value)
    {
        $hasil = 0;
        if ($type == self::TIDAK_LULUS) {
            $min = 63;
            $max = 70;
            // if ($value >= 1) {
            //     $hasil = $min;
            // } elseif ($value <= 0) {
            //     $hasil = $max;
            // } else {
            // }
            $hasil = $max - ($value * ($max - $min));
        }

        // if ($type == self::TIDAK_LULUS) {
        //     $min = 61;
        //     $mid = 65;
        //     $max = 70;
        //     if ($value >= 1) {
        //         $hasil = $mid;
        //     } elseif ($value <= 0) {
        //         $hasil = $min;
        //     } elseif ($value < 1) {
        //         $hasil = $mid - ($value * ($mid - $min));
        //     } else {
        //         $hasil = $mid + ($value * ($max - $mid));
        //     }
        // }

        if ($type == self::LULUS) {
            $min = 63;
            $max = 70;
            // if ($value >= 1) {
            //     $hasil = $max;
            // } elseif ($value <= 0) {
            //     $hasil = $min;
            // } else {
            // }
            $hasil = $min + ($value * ($max - $min));
        }

        return $hasil;
    }

    public static function getLabelHasil($value)
    {
        $min = 63;
        $max = 70;
        $hasilTidakLulus = self::persamaan(self::PERSAMAAN_KURANG_DARI, $value, $min, $max);
        $hasilLulus = self::persamaan(self::PERSAMAAN_LEBIH_DARI, $value, $min, $max);

        if ($hasilTidakLulus > $hasilLulus) {
            return 'Tidak Lulus';
        } else {
            return 'Lulus PTU';
        }
    }
}
