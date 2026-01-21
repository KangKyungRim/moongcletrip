<div class="spinner-wrap" style="display: none;" id="loading">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only"></span>
    </div>
</div>

<script>
    const loading = document.getElementById('loading');

    const observer = new MutationObserver(() => {
    const display = getComputedStyle(loading).display;
    if (display === 'flex') {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
    });

    observer.observe(loading, { attributes: true, attributeFilter: ['style'] });
</script>