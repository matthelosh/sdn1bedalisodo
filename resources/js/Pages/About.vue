<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { defineAsyncComponent } from 'vue';

const page = usePage();
// import Navbar from `@/Layouts/Front/${page.props.layout}/Navbar.vue`;
// import Footer from `@/Layouts/Front/${page.props.layout}/Footer.vue`;
const Navbar = defineAsyncComponent(() => import(`./../Layouts/Front/${page.props.layout}/Home/Navbar.vue`))
const Footer = defineAsyncComponent(() => import(`./../Layouts/Front/${page.props.layout}/Home/Footer.vue`))

</script>

<template>
<Head>
    <title>Tentang SD Negeri 1 Bedalisodo</title>
    <meta name="description" content="Profil SD Negeri 1 Bedalisodo Kecamatan Wagir Kabupaten Malang. SD Negeri 1 Bedalisodo menjalankan misi pendidikan untuk mencerdaskan putra-putri bangsa, khususnya di lingkungan desa Dalisodo Kecamatan Wagir. Menyediakan program pendidikan intrakurikuler formal dan ekstra kurikuler yang mendukung bakat dan minat peserta didik serta memberdayakan kearifan lokal dengan menerapkan bertutur bahasa jawa di hari tertentu dan membekali peserta didik dengan keterampilan teknologi informasi yang dibutuhkan dalam kehidupan di era globalisasi." />
</Head>

<div class="wrapper w-full  pt-12 md:pt-0 pb-8 bg-white bg-opacity-100 ">
    <Navbar />
    <div class="content w-full md:w-8/12 mx-auto  bg-opacity-95 backdrop-blur p-4 my-8">
        <h1 class="text-xl md:text-6xl font-light text-slate-800 font-serif py-4 px-4 md:px-0 tracking-wider w-full">Profil SD Negeri 1 Bedalisodo</h1>
        <p>SD Negeri 1 Bedalisodo menjalankan misi pendidikan untuk mencerdaskan putra-putri bangsa, khususnya di lingkungan desa Dalisodo Kecamatan Wagir. Menyediakan program pendidikan intrakurikuler formal dan ekstra kurikuler yang mendukung bakat dan minat peserta didik serta memberdayakan kearifan lokal dengan menerapkan bertutur bahasa jawa di hari tertentu dan membekali peserta didik dengan keterampilan teknologi informasi yang dibutuhkan dalam kehidupan di era globalisasi.</p>
        <div class="feature-image w-full mx-auto relative h-[200px] md:h-[500px]  border-b-2 border-gray-50 shadow-inner rounded transition-all my-4 md:my-8" :style="`background: url('/img/halaman.jpg'); background-repeat:no-repeat; background-size: cover; background-attachment: fixed;`">
          <div class="img-mask z-5 absolute top-0 right-0 bottom-0 left-0 flex items-center justify-center bg-white bg-opacity-10  hover:bg-opacity-0  transition-all">
          </div>
        </div>
        <p>
            <figure class="w-[20%] float-left mr-4 shadow border-1 border-gray-200">
                <img src="/img/ks.jpg" alt="" class="w-full">
                <figcaption class="bg-gray-100 py-2">
                    <h1 class="text-center font-bold underline leading-6">Nurul Huda, S. Pd.SD</h1>
                    <h3 class="text-center  leading-3">Kepala Sekolah</h3>
                </figcaption>
            </figure>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere, nisi laudantium laborum atque aliquam, magnam iusto aperiam distinctio est velit accusamus! Repudiandae deleniti inventore sed earum delectus provident quasi enim. Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita officiis molestias ut voluptates! Itaque quos asperiores, ipsam odio voluptates earum nemo vitae cumque molestias iure repellendus voluptatibus, modi, cupiditate magni. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sequi, at accusantium explicabo cumque impedit earum dolor sint velit, magni ipsum et quibusdam? Perferendis doloremque molestiae totam aperiam aut, repellendus quam? Lorem ipsum dolor sit amet consectetur, adipisicing elit. Deleniti dignissimos accusamus eos perspiciatis dicta inventore saepe atque quibusdam totam explicabo at assumenda voluptates, eum, deserunt, dolore reprehenderit magnam consectetur corporis. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maiores odit voluptate nostrum cupiditate quisquam, cum harum provident doloremque eveniet ratione inventore sit quidem minima sapiente, deleniti accusantium obcaecati. Neque, aliquid.</p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit mollitia eum magnam accusantium architecto sit, alias temporibus aspernatur repellendus! Omnis velit facilis pariatur illo fugit magnam quam voluptatum, sint beatae. Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum possimus dolor sed a eligendi amet, magnam ad architecto nobis, recusandae velit alias deleniti enim. Iure dolor sapiente rerum ad voluptatibus! Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias quam mollitia consequatur perferendis earum voluptatibus non sunt debitis delectus dolores dicta nulla, tenetur similique quia ipsam reprehenderit explicabo. Quam, necessitatibus.</p>
    </div>
</div>
<Footer />
</template>
<style scoped>
    /* .wrapper {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' version='1.1' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:svgjs='http://svgjs.dev/svgjs' width='1440' height='560' preserveAspectRatio='none' viewBox='0 0 1440 560'%3e%3cg mask='url(%26quot%3b%23SvgjsMask1000%26quot%3b)' fill='none'%3e%3crect width='1440' height='560' x='0' y='0' fill='%230e2a47'%3e%3c/rect%3e%3cpath d='M573.29 2.02 a34.53 34.53 0 1 0 69.06 0 a34.53 34.53 0 1 0 -69.06 0z' fill='%23e73635'%3e%3c/path%3e%3cpath d='M1061.66 518.79L1112.12 518.79L1112.12 569.25L1061.66 569.25z' stroke='%23d3b714'%3e%3c/path%3e%3cpath d='M605.78 316.57 a41.22 41.22 0 1 0 82.44 0 a41.22 41.22 0 1 0 -82.44 0z' fill='%23037b0b'%3e%3c/path%3e%3cpath d='M608.35 67.58L633.56 67.58L633.56 92.79L608.35 92.79z' fill='%23d3b714'%3e%3c/path%3e%3cpath d='M871.3 490.7a39.73 39.73 0 1 0-40.33 68.46z' stroke='%23e73635'%3e%3c/path%3e%3cpath d='M214.88 213.52 a19.56 19.56 0 1 0 39.12 0 a19.56 19.56 0 1 0 -39.12 0z' fill='%23d3b714'%3e%3c/path%3e%3cpath d='M1014.09 174.54L1040.81 174.54L1040.81 201.26L1014.09 201.26z' fill='%23e73635'%3e%3c/path%3e%3cpath d='M694.43 162.44L732.14 162.44L732.14 200.15L694.43 200.15z' stroke='%23e73635'%3e%3c/path%3e%3cpath d='M314.42 560L355.22 560L355.22 600.8L314.42 600.8z' fill='%23d3b714'%3e%3c/path%3e%3cpath d='M582.66 30.28L587.26 30.28L587.26 38.34L582.66 38.34z' stroke='%23e73635'%3e%3c/path%3e%3cpath d='M847.47 95.54L883.32 95.54L883.32 143.19L847.47 143.19z' stroke='%23e73635'%3e%3c/path%3e%3cpath d='M266.38 561.53a12.08 12.08 0 1 0 18.33-15.74z' stroke='%23037b0b'%3e%3c/path%3e%3cpath d='M1077.56 418.97L1105.26 418.97L1105.26 424.58L1077.56 424.58z' stroke='%23037b0b'%3e%3c/path%3e%3cpath d='M474.35 199.54L492.05 199.54L492.05 212.98L474.35 212.98z' stroke='%23037b0b'%3e%3c/path%3e%3cpath d='M633.47 559.79L684.25 559.79L684.25 591.8L633.47 591.8z' fill='%23037b0b'%3e%3c/path%3e%3cpath d='M640.21 263.67L671.26 263.67L671.26 299.44L640.21 299.44z' stroke='%23d3b714'%3e%3c/path%3e%3cpath d='M188.05 221.8L218.28 221.8L218.28 268.59L188.05 268.59z' fill='%23d3b714'%3e%3c/path%3e%3cpath d='M40.61 392.87L50.76 392.87L50.76 426.08L40.61 426.08z' stroke='%23e73635'%3e%3c/path%3e%3cpath d='M565.67 184.22a29.14 29.14 0 1 0 31.01-49.35z' fill='%23037b0b'%3e%3c/path%3e%3cpath d='M96.09 248.69 a39.84 39.84 0 1 0 79.68 0 a39.84 39.84 0 1 0 -79.68 0z' fill='%23e73635'%3e%3c/path%3e%3cpath d='M303.24 469.61L322.94 469.61L322.94 507.6L303.24 507.6z' fill='%23e73635'%3e%3c/path%3e%3cpath d='M1436.45 49.24L1444.48 49.24L1444.48 57.27L1436.45 57.27z' stroke='%23d3b714'%3e%3c/path%3e%3cpath d='M1220.61 269.53L1234.34 269.53L1234.34 283.26L1220.61 283.26z' fill='%23037b0b'%3e%3c/path%3e%3cpath d='M181.83 530.61 a33.05 33.05 0 1 0 66.1 0 a33.05 33.05 0 1 0 -66.1 0z' stroke='%23e73635'%3e%3c/path%3e%3cpath d='M57.29 385.74L92.99 385.74L92.99 431.92L57.29 431.92z' fill='%23d3b714'%3e%3c/path%3e%3cpath d='M310.29 78.48 a29.41 29.41 0 1 0 58.82 0 a29.41 29.41 0 1 0 -58.82 0z' fill='%23e73635'%3e%3c/path%3e%3cpath d='M62.28 472.16 a48.24 48.24 0 1 0 96.48 0 a48.24 48.24 0 1 0 -96.48 0z' stroke='%23037b0b'%3e%3c/path%3e%3cpath d='M1191.35 275.68L1246.95 275.68L1246.95 306.4L1191.35 306.4z' stroke='%23e73635'%3e%3c/path%3e%3cpath d='M788.98 543.34a33.47 33.47 0 1 0-66.14 10.32z' fill='%23037b0b'%3e%3c/path%3e%3cpath d='M271.01 88.4a32.27 32.27 0 1 0 42.83 48.28z' stroke='%23e73635'%3e%3c/path%3e%3cpath d='M260.42 17.22L297.84 17.22L297.84 43.79L260.42 43.79z' stroke='%23d3b714'%3e%3c/path%3e%3cpath d='M1206.85 477.83L1207.43 477.83L1207.43 494.04L1206.85 494.04z' stroke='%23037b0b'%3e%3c/path%3e%3cpath d='M335.67 260.02L380.52 260.02L380.52 283.59L335.67 283.59z' stroke='%23e73635'%3e%3c/path%3e%3c/g%3e%3cdefs%3e%3cmask id='SvgjsMask1000'%3e%3crect width='1440' height='560' fill='white'%3e%3c/rect%3e%3c/mask%3e%3c/defs%3e%3c/svg%3e");
        background-attachment: fixed;
    } */

    .content p {
        text-align: justify;
    }
</style>