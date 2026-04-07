<section class="wish-section" id="wish">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <h2 class="section-title">Ucapan & Doa</h2>
            <div class="section-divider">
                <span></span>
                <i class="fas fa-comment-dots"></i>
                <span></span>
            </div>
            <p class="section-subtitle">
            </p>
        </div>
        
        <div class="row">
            <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
                <div class="wish-form">
                    <h4>Tulis Ucapan & Konfirmasi Kehadiran</h4>
                    <form id="wishForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control" name="guest_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ucapan & Doa *</label>
                            <textarea class="form-control" name="message" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Kehadiran *</label>
                            <select class="form-control" name="attendance" required>
                                <option value="yes">Hadir</option>
                                <option value="no">Tidak Hadir</option>
                                <option value="maybe">Masih Ragu</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Yang Hadir</label>
                            <input type="number" class="form-control" name="attendance_count" value="1" min="1" max="10">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane"></i> Kirim Ucapan
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <div class="wish-list">
                    <h4>Ucapan & Doa Untuk Pengantin</h4>
                    <div id="wishesContainer">
                        @forelse($wishes as $wish)
                        <div class="wish-item">
                            <div class="wish-header">
                                <strong class="wish-name">{{ $wish->guest_name }}</strong>
                                <span class="wish-date">{{ $wish->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="wish-message">{{ $wish->message }}</p>
                            <div class="wish-attendance">
                                @if($wish->attendance == 'yes')
                                    <span class="badge bg-success">Hadir ({{ $wish->attendance_count }} orang)</span>
                                @elseif($wish->attendance == 'no')
                                    <span class="badge bg-danger">Tidak Hadir</span>
                                @else
                                    <span class="badge bg-warning">Masih Ragu</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-comment-dots fs-1 mb-2"></i>
                            <p>Belum ada ucapan. Jadilah yang pertama memberikan doa restu.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.getElementById('wishForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
        
        const formData = new FormData(this);
        formData.append('_token', window.invitationData.csrfToken);
        
        try {
            const response = await fetch(window.invitationData.wishUrl, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Terima Kasih!',
                    text: 'Ucapan dan doa Anda telah terkirim',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Add new wish to list
                const wishesContainer = document.getElementById('wishesContainer');
                const emptyMessage = wishesContainer.querySelector('.text-center');
                if (emptyMessage && emptyMessage.querySelector('p')?.innerText.includes('Belum ada ucapan')) {
                    wishesContainer.innerHTML = '';
                }
                
                const newWish = document.createElement('div');
                newWish.className = 'wish-item';
                newWish.style.opacity = '0';
                newWish.style.transform = 'translateY(20px)';
                newWish.innerHTML = `
                    <div class="wish-header">
                        <strong class="wish-name">${escapeHtml(formData.get('guest_name'))}</strong>
                        <span class="wish-date">Baru saja</span>
                    </div>
                    <p class="wish-message">${escapeHtml(formData.get('message'))}</p>
                    <div class="wish-attendance">
                        ${formData.get('attendance') === 'yes' ? 
                            `<span class="badge bg-success">Hadir (${formData.get('attendance_count')} orang)</span>` : 
                            formData.get('attendance') === 'no' ?
                            '<span class="badge bg-danger">Tidak Hadir</span>' :
                            '<span class="badge bg-warning">Masih Ragu</span>'}
                    </div>
                `;
                wishesContainer.insertBefore(newWish, wishesContainer.firstChild);
                
                setTimeout(() => {
                    newWish.style.opacity = '1';
                    newWish.style.transform = 'translateY(0)';
                    newWish.style.transition = 'all 0.3s ease';
                }, 10);
                
                this.reset();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: result.message || 'Terjadi kesalahan, silakan coba lagi'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan, silakan coba lagi'
            });
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
@endpush