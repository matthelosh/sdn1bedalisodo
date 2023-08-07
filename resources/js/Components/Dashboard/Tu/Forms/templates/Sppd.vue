<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, defineAsyncComponent, computed } from 'vue';

const page = usePage()
const Kop = defineAsyncComponent(() => import('@/Components/General/Kop.vue'))
</script>

<template>
    <div class="paper font-serif break-after-page bg-white p-4 shadow-lg print:shadow-none print:p-0 my-2 print:my-0 print:mx-4">
        <Kop />
        <div class="meta my-8">
            <h1 class="text-center font-bold underline text-xl tracking-wide">SURAT TUGAS</h1>
            <p class="text-center leading-3">Nomor: 800/.../421.102.408.012/2023</p>
        </div>
        <div class="content">
            <p>Yang bertanda tangan di bawah ini:</p>
            <table class="ml-8">
                <tr>
                    <td>Nama</td>
                    <td class="px-2">:</td>
                    <td> {{ page.props.sekolah.ks.nama }}, {{page.props.sekolah.ks.gelar_belakang}}</td>
                </tr>
                <tr>
                    <td>NIP</td>
                    <td class="px-2">:</td>
                    <td>{{ page.props.sekolah.ks.nip }}</td>
                </tr>
                <tr>
                    <td>Pangkat, Gol. Ruang</td>
                    <td class="px-2">:</td>
                    <td>{{ page.props.sekolah.ks.pangkat ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td class="px-2">:</td>
                    <td>Kepala Sekolah</td>
                </tr>
                <tr>
                    <td>Alamat Dinas</td>
                    <td class="px-2">:</td>
                    <td>{{page.props.sekolah.nama}}</td>
                </tr>
            </table>
            <p class="text-center">Menugaskan</p>
            <table class="border mx-auto">
                <thead>
                    <tr>
                        <th class="border border-gray-600 p-2">No</th>
                        <th class="border border-gray-600 p-2">Nama</th>
                        <th class="border border-gray-600 p-2">Jabatan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-600 p-1 text-center">1</td>
                        <td class="border border-gray-600 p-2">
                            <p class="font-bold">Muhammad Soleh, S. PdI.</p>
                            <p class="leading-3">NIP. 19840703 201903 1 007</p>
                        </td>
                        <td class="border border-gray-600 p-2">Guru PAI</td>
                    </tr>
                </tbody>
            </table>
            <br />
            <table class="ml-8">
                <tr>
                    <td>Unit Kerja</td>
                    <td class="px-2">:</td>
                    <td>{{ page.props.sekolah.nama }}</td>
                </tr>
                <tr>
                    <td>Hari, Tanggal</td>
                    <td class="px-2">:</td>
                    <td>Senin, 07 Agustus 2023</td>
                </tr>
                <tr>
                    <td>Pukul</td>
                    <td class="px-2">:</td>
                    <td>08.00 s/d Selesai</td>
                </tr>
                <tr>
                    <td>Tempat</td>
                    <td class="px-2">:</td>
                    <td>KORWIL DINAS PENDIDIKAN KEC. WAGIR</td>
                </tr>
                <tr>
                    <td>Acara</td>
                    <td class="px-2">:</td>
                    <td>REKON BOS</td>
                </tr>
            </table>
            <p>Demikan surat tugas ini dibuat, agar dilaksanakan dengan penuh tanggung jawab.</p>

            <div class="grid grid-cols-3 my-16">
                <div></div>
                <div></div>
                <div>
                    <p>Dalisodo, {{ new Date().toLocaleDateString("id-ID") }}</p>
                    <p>Kepala Sekolah,</p>
                    <br />

                    <p class="font-bold underline underline-offset-4 tracking-wider mt-6">{{ page.props.sekolah.ks.nama }}, {{ page.props.sekolah.ks.gelar_belakang }}</p>
                    <p class="leading-4">NIP. {{ page.props.sekolah.ks.nip }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="paper font-serif break-after-page bg-white p-4 shadow-lg print:shadow-none print:p-0 my-2 print:my-0">
        <Kop />
        <div class="meta my-8">
            <h1 class="font-bold text-center underline underline-offset-2">SURAT PERINTAH PERJALANAN DINAS (SPPD)</h1>
            <p class="text-center leading-3">Nomor: 800/..../421.102.408.012/2023</p>
        </div>
        <div class="content">
            <table class="border w-full">
                <tr>
                    <td class="border border-gray-800 text-center p-2">1</td>
                    <td class="border border-gray-800 p-1">Pejabat berwenang yang memberi perintah</td>
                    <td class="border border-gray-800 p-1">Kepala {{ page.props.sekolah.nama }}</td>
                </tr>
                <tr>
                    <td class="border border-gray-800 text-center p-2">2</td>
                    <td class="border border-gray-800 p-1">Nama/NIP Pegawai yang diperintah</td>
                    <td class="border border-gray-800 p-1">Terlampir</td>
                </tr>
                <tr>
                    <td class="border border-gray-800 text-center p-2">3</td>
                    <td class="border border-gray-800 p-1">
                        <ol class=" list-[lower-alpha] list-inside">
                            <li>Pangkat dan golongan ruang gaji menurut PP. NO. 6 tahun 1997</li>
                            <li>Jabatan / Instansi</li>
                            <li>Tingkat menurut peraturan perjalanan dinas</li>
                        </ol>
                    </td>
                    <td class="border border-gray-800 p-1">
                        <ol>
                            <li>-</li>
                            <li>-</li>
                            <li>-</li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td class="border border-gray-800 text-center p-2">4</td>
                    <td class="border border-gray-800 p-1">Maksud perjalanan dinas</td>
                    <td class="border border-gray-800 p-1">Terlampir</td>
                </tr>
                <tr>
                    <td class="border border-gray-800 text-center p-2">5</td>
                    <td class="border border-gray-800 p-1">Alat angkutan yang diperlukan</td>
                    <td class="border border-gray-800 p-1">Sepeda Motor</td>
                </tr>
                <tr>
                    <td class="border border-gray-800 text-center p-2">6</td>
                    <td class="border border-gray-800 p-1">
                        <ol class=" list-[lower-alpha] list-inside">
                            <li>Tempat berangkat</li>
                            <li>Tempat Tujuan</li>
                        </ol>
                    </td>
                    <td class="border border-gray-800 p-1">
                        <ol>
                            <li>Dalisodo Kec. Wagir</li>
                            <li>Korwil Kec. Wagir</li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td class="border border-gray-800 text-center p-2">7</td>
                    <td class="border border-gray-800 p-1">
                        <ol class=" list-[lower-alpha] list-inside">
                            <li>Lamanya perjalanan</li>
                            <li>Tanggal berangkat</li>
                            <li>Tanggal harus kembali</li>
                        </ol>
                    </td>
                    <td class="border border-gray-800 p-1">
                        <ol class=" list-[lower-alpha] list-inside">
                            <li>1 hari</li>
                            <li>Senin, 26 Juni 2023</li>
                            <li>Senin, 26 Juni 2023</li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td class="border border-gray-800 text-center p-2">8</td>
                    <td class="border border-gray-800 p-1">Pengikut</td>
                    <td class="border border-gray-800 p-1">-</td>
                </tr>
                <tr>
                    <td class="border border-gray-800 text-center p-2">9</td>
                    <td class="border border-gray-800 p-1">
                        Pembebanan anggaran:
                        <ol class=" list-[lower-alpha] list-inside">
                            <li>Instansi</li>
                        </ol>
                    </td>
                    <td class="border border-gray-800 p-1">
                        <p>&nbsp;</p>
                        <ol class=" list-[lower-alpha] list-inside">
                            <li>SD Negeri 1 Bedalisodo</li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td class="border border-gray-800 text-center p-2">10</td>
                    <td class="border border-gray-800 p-1">Pengikut</td>
                    <td class="border border-gray-800 p-1">Surat perintah ini supaya dilaksanakan dengan penuh rasa tanggung jawab</td>
                </tr>
            </table>
        </div>
        <div class="ttd grid grid-cols-3 mt-16">
            <div></div>
            <div></div>
            <div>
                <table>
                    <tr>
                        <td>Dikeluarkan di</td>
                        <td>:</td>
                        <td>Dalisodo</td>
                    </tr>
                    <tr>
                        <td>Pada Tanggal</td>
                        <td>:</td>
                        <td>26 Juni 2023</td>
                    </tr>
                </table>
                <p>Kepala Sekolah</p>
                <br />
                <h3 class="mt-14 underline font-bold tracking-wide">{{ page.props.sekolah.ks.nama }}, {{ page.props.sekolah.ks.gelar_belakang }}</h3>
                <p class="leading-3 tracking-wide">NIP. {{ page.props.sekolah.ks.nip }}</p>
            </div>
        </div>
    </div>
    <div class="paper font-serif break-after-page bg-white p-4 shadow-lg print:shadow-none print:p-0 my-2 print:my-0">
        <div class="meta grid grid-cols-3">
            <div></div>
            <div></div>
            <div>
                <table>
                    <tr>
                        <td>Berangkat dari</td>
                        <td class="px-3">:</td>
                        <td>Dalisodo</td>
                    </tr>
                    <tr>
                        <td>Pada tanggal</td>
                        <td class="px-3">:</td>
                        <td>26 Juni 2023</td>
                    </tr>
                    <tr>
                        <td>Ke</td>
                        <td class="px-3">:</td>
                        <td>Korwil Wagir</td>
                    </tr>
                </table>
                <p class="mt-2">Kepala {{ page.props.sekolah.nama }}</p>
                <br />
                <h3 class="font-bold underline mt-14">{{ page.props.sekolah.ks.nama }}, {{ page.props.sekolah.ks.gelar_belakang }}</h3>
                <p class="leading-3">NIP {{ page.props.sekolah.ks.nip }}</p>
                
            </div>
        </div>
        <div class="content w-full mt-6">
            <table class="w-full border border-black">
                
                <tr>
                    <td class="align-top p-3 text-center border-b border-black">1</td>
                    <td class="border-e border-black align-top p-3 border-b">
                        <table>
                            <tr>
                                <td>Tiba di</td>
                                <td>:</td>
                                <td>Korwil Kec. Wagir</td>
                            </tr>
                            <tr>
                                <td>Pada tanggal</td>
                                <td>:</td>
                                <td>26 Juni 2023</td>
                            </tr>
                        </table>
                        <p class="mt-6">Mengetahui Pihak Lembaga (Instansi):</p>
                        <br />
                        <p class="underline font-bold mt-10">.........................................</p>
                        <p>NIP ..........................................</p>
                    </td>
                    <td class="border-b border-black align-top p-3 w-[50%]">
                        <table>
                            <tr>
                                <td>Berangkat (kembali) dari</td>
                                <td>:</td>
                                <td>Korwil Kec. Wagir</td>
                            </tr>
                            <tr>
                                <td>pada tanggal</td>
                                <td>:</td>
                                <td>26 Juni 2023</td>
                            </tr>
                            <tr>
                                <td>Ke</td>
                                <td>:</td>
                                <td>Dalisodo</td>
                            </tr>
                        </table>
                        <p>Mengetahui Pihak Lembaga (Instansi):</p>
                        <br />
                        <p class="underline font-bold mt-10">.........................................</p>
                        <p>NIP .....................................</p>
                    </td>
                </tr>
                <tr>
                    <td class="align-top p-3 text-center border-b border-black">2</td>
                    <td class="border-e border-black align-top p-3 border-b">
                        <table>
                            <tr>
                                <td>Tiba kembali di</td>
                                <td>:</td>
                                <td>Dalisodo</td>
                            </tr>
                            <tr>
                                <td>Pada tanggal</td>
                                <td>:</td>
                                <td>26 Juni 2023</td>
                            </tr>
                        </table>
                        <p>Kepala {{ page.props.sekolah.nama }}</p>
                        <br />
                        <h3 class="font-bold underline mt-10">{{ page.props.sekolah.ks.nama }}, {{ page.props.sekolah.ks.gelar_belakang }}</h3>
                        <p class="leading-3">NIP {{ page.props.sekolah.ks.nip }}</p>
                    </td>
                    <td class="border-b border-black align-top p-3">
                        Telah diperiksa dengan keterangan perjalanan tersebutdi atas benar dilakukan atas perintahnya dan semata-mata untuk kepentingan jabatan dalam waktu yang sesingkat-singkatnya.
                    </td>
                </tr>
                <tr>
                    <td class="align-top p-3 text-center border-b border-black">3</td>
                    <td class="border-e border-black align-top p-3 border-b">Catatan Lain</td>
                    <td class="border-b border-black align-top p-14"></td>
                </tr>
                <tr>
                    <td class="align-top p-3 text-center border-b border-black">4</td>
                    <td colspan="2" class="p-3">
                        Pejabat yang berwenang menerbitkan SPPD, pegawai yang melakukan perjalanan dinas, para Pejabat yang mengesahkan tanggal berangkat/tiba serta bendaharawan bertanggung-jawab berdasarkan peraturan-peraturan Keuangan Negara, apabila Negara menderita rugi akibat kesalahan, kelalaian dan kealpaan (angka 3, Lampiran Surat Edaran Menteri Keuangan tanggal 30 April 1974, No. B 296/MK/1/4/1974)
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>