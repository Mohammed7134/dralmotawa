function copyToClipboard(e) {
    try {
        const id = e.id.split('-')[1];
        const textarea = document.getElementById(id);
        textarea.style.display = 'block';
        textarea.select();
        const successful = document.execCommand('copy');
        textarea.style.display = 'none';
        const message = successful ? 'تم النسخ' : 'لم يتم النسخ';
        showSnackbar(message);
    } catch (err) {
        console.error('Failed to copy text:', err);
    }

}