export async function initPdfViewer(el) {
    const fileUrl = el.dataset.pdfUrl;
    const [pdfjsLib, { default: workerSrc }] = await Promise.all([
        import('pdfjs-dist'),
        import('pdfjs-dist/build/pdf.worker.mjs?url'),
    ]);

    pdfjsLib.GlobalWorkerOptions.workerSrc = workerSrc;

    const canvas = el.querySelector('[data-pdf-canvas]');
    const ctx = canvas.getContext('2d');
    const pageLabel = el.querySelector('[data-pdf-page-label]');
    const loadingEl = el.querySelector('[data-pdf-loading]');
    const errorEl = el.querySelector('[data-pdf-error]');

    let pdfDoc = null;
    let currentPage = 1;
    let scale = 1.2;
    let renderTask = null;

    async function renderPage(num) {
        if (renderTask) {
            renderTask.cancel();
        }

        const page = await pdfDoc.getPage(num);
        const viewport = page.getViewport({ scale });
        canvas.width = viewport.width;
        canvas.height = viewport.height;

        renderTask = page.render({ canvasContext: ctx, viewport });
        await renderTask.promise;

        currentPage = num;
        pageLabel.textContent = `${currentPage} / ${pdfDoc.numPages}`;
    }

    el.addEventListener('pdf-prev', () => currentPage > 1 && renderPage(currentPage - 1));
    el.addEventListener('pdf-next', () => currentPage < pdfDoc.numPages && renderPage(currentPage + 1));
    el.addEventListener('pdf-zoom-in', () => { scale = Math.min(scale + 0.2, 3); renderPage(currentPage); });
    el.addEventListener('pdf-zoom-out', () => { scale = Math.max(scale - 0.2, 0.5); renderPage(currentPage); });

    try {
        pdfDoc = await pdfjsLib.getDocument({ url: fileUrl }).promise;
        loadingEl?.classList.add('hidden');
        await renderPage(1);
    } catch (e) {
        loadingEl?.classList.add('hidden');
        errorEl?.classList.remove('hidden');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-pdf-viewer]').forEach(initPdfViewer);
});
