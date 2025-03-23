let lastFileCount = null;

async function checkFileCount() {
    try {
        const response = await fetch('?file_count');
        const fileCount = await response.text();

        if (lastFileCount !== null && parseInt(fileCount) !== lastFileCount) {
            location.reload();
        }

        lastFileCount = parseInt(fileCount);
    } catch (error) {
        console.error("Dosya sayısı kontrol edilemedi:", error);
    }
}

setInterval(checkFileCount, 2000);
