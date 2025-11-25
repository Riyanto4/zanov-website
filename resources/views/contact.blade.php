@extends('layouts.guest.main')

@section('content')
<!-- Hero Section -->
<section class="relative h-64 flex items-center justify-center overflow-hidden bg-primary">
    <div class="absolute inset-0 bg-black/30 z-10"></div>
    <div class="relative z-20 text-center px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Hubungi Kami</h1>
        <p class="text-lg md:text-xl">Kami siap membantu Anda</p>
    </div>
</section>

<!-- Contact Information -->
<section class="py-20 bg-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Info -->
            <div>
                <h2 class="text-3xl font-bold mb-6 uppercase">Informasi Kontak</h2>
                <p class="text-lg mb-8 text-white-600 leading-relaxed">
                    Kami sangat senang mendengar dari Anda! Apakah Anda memiliki pertanyaan tentang produk kami, 
                    memerlukan bantuan dengan pesanan, atau hanya ingin memberikan masukan, tim kami siap membantu.
                </p>

                <div class="space-y-6">
                    <!-- Address -->
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Alamat</h3>
                            <p class="text-white-600">
                                Jl. Merdeka No. 123<br>
                                Jakarta Pusat, DKI Jakarta 10110<br>
                                Indonesia
                            </p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Telepon</h3>
                            <p class="text-white-600">
                                +62 21 1234 5678<br>
                                +62 812 3456 7890
                            </p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Email</h3>
                            <p class="text-white-600">
                                info@zanov.com<br>
                                support@zanov.com
                            </p>
                        </div>
                    </div>

                    <!-- Working Hours -->
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Jam Operasional</h3>
                            <p class="text-white-600">
                                Senin - Jumat: 09:00 - 18:00<br>
                                Sabtu: 10:00 - 16:00<br>
                                Minggu: Tutup
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-white p-8 shadow-lg">
                <h2 class="text-3xl font-bold mb-6 uppercase">Kirim Pesan</h2>
                <form id="contactForm" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-bold mb-2 uppercase tracking-wide text-black">Nama Lengkap</label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-4 py-3 border border-gray-300 focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition duration-300 text-black"
                               placeholder="Masukkan nama lengkap Anda">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold mb-2 uppercase tracking-wide text-black">Email</label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-4 py-3 border border-gray-300 focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition duration-300 text-black"
                               placeholder="Masukkan email Anda">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-bold mb-2 uppercase tracking-wide text-black">Nomor Telepon</label>
                        <input type="tel" id="phone" name="phone" required
                               class="w-full px-4 py-3 border border-gray-300 focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition duration-300 text-black"
                               placeholder="Masukkan nomor telepon Anda">
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-bold mb-2 uppercase tracking-wide text-black">Subjek</label>
                        <input type="text" id="subject" name="subject" required
                               class="w-full px-4 py-3 border border-gray-300 focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition duration-300 text-black"
                               placeholder="Masukkan subjek pesan">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-bold mb-2 uppercase tracking-wide text-black">Pesan</label>
                        <textarea id="message" name="message" rows="5" required
                                  class="w-full px-4 py-3 border border-gray-300 focus:border-accent focus:ring-2 focus:ring-accent/20 outline-none transition duration-300 resize-none text-black"
                                  placeholder="Tulis pesan Anda di sini..."></textarea>
                    </div>

                    <button type="submit" 
                            class="w-full bg-accent text-primary px-8 py-4 font-bold uppercase tracking-wider hover:bg-gray-800 hover:text-accent border-2 border-accent transition duration-300 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Kirim via WhatsApp
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-20 bg-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold mb-8 text-center uppercase">Lokasi Kami</h2>
        <div class="w-full h-96 shadow-lg overflow-hidden">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3956.8287!2d109.248120!3d-7.456134!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zN8KwMjcnMjIuMSJTIDEwOcKwMTQnNTMuMiJF!5e0!3m2!1sen!2sid!4v1732528013000!5m2!1sen!2sid" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-secondary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold mb-12 text-center uppercase">Pertanyaan yang Sering Diajukan</h2>
        <div class="space-y-6">
            <div class="bg-white p-6 shadow-md">
                <h3 class="text-xl font-bold mb-3 text-black">Berapa lama waktu pengiriman?</h3>
                <p class="text-gray-600">
                    Waktu pengiriman standar adalah 3-5 hari kerja untuk wilayah Jakarta dan sekitarnya, 
                    dan 5-7 hari kerja untuk wilayah luar Jakarta. Untuk pengiriman express, 
                    pesanan akan tiba dalam 1-2 hari kerja.
                </p>
            </div>

            <div class="bg-white p-6 shadow-md">
                <h3 class="text-xl font-bold mb-3 text-black">Apakah ada garansi untuk produk?</h3>
                <p class="text-gray-600">
                    Ya, semua produk ZANOV dilengkapi dengan garansi 6 bulan untuk cacat produksi. 
                    Garansi tidak mencakup kerusakan akibat pemakaian normal atau kesalahan penggunaan.
                </p>
            </div>

            <div class="bg-white p-6 shadow-md">
                <h3 class="text-xl font-bold mb-3 text-black">Bagaimana cara menukar atau mengembalikan produk?</h3>
                <p class="text-gray-600">
                    Anda dapat menukar atau mengembalikan produk dalam waktu 14 hari sejak tanggal pembelian, 
                    dengan syarat produk masih dalam kondisi baru dan belum dipakai. Silakan hubungi 
                    customer service kami untuk proses penukaran atau pengembalian.
                </p>
            </div>

            <div class="bg-white p-6 shadow-md">
                <h3 class="text-xl font-bold mb-3 text-black">Apakah tersedia layanan custom order?</h3>
                <p class="text-gray-600">
                    Ya, kami menyediakan layanan custom order untuk pemesanan dalam jumlah tertentu. 
                    Silakan hubungi tim kami untuk informasi lebih lanjut mengenai layanan custom order 
                    dan estimasi waktu pengerjaannya.
                </p>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value;
    
    const whatsappMessage = `*Pesan dari Website ZANOV*%0A%0A` +
                           `*Nama:* ${name}%0A` +
                           `*Email:* ${email}%0A` +
                           `*Telepon:* ${phone}%0A` +
                           `*Subjek:* ${subject}%0A%0A` +
                           `*Pesan:*%0A${message}`;
    
    const whatsappNumber = '62895383027843';
    
    const whatsappURL = `https://wa.me/${whatsappNumber}?text=${whatsappMessage}`;
    
    window.open(whatsappURL, '_blank');
    
});
</script>
@endsection
