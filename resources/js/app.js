import './bootstrap';
import './pdf-viewer';
import Alpine from 'alpinejs';

window.setSisukatTheme = function (theme) {
    localStorage.setItem('sisukat-theme', theme);
    const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
    document.documentElement.classList.toggle('dark', isDark);
};

window.Alpine = Alpine;
Alpine.start();

// Quill dimuat lazy (dynamic import) supaya halaman publik tidak ikut
// mengunduh bundle rich-text editor yang hanya dipakai di admin.
document.addEventListener('DOMContentLoaded', async () => {
    const editorEl = document.querySelector('[data-rich-editor]');
    if (!editorEl) {
        return;
    }

    const [{ default: Quill }] = await Promise.all([
        import('quill'),
        import('quill/dist/quill.snow.css'),
    ]);

    const hiddenInput = document.getElementById(editorEl.dataset.richEditor);
    const TableIcon = '<svg viewBox="0 0 18 18"><rect class="ql-stroke" height="12" width="12" x="3" y="3"></rect><line class="ql-stroke" x1="3" x2="15" y1="9" y2="9"></line><line class="ql-stroke" x1="9" x2="9" y1="3" y2="15"></line></svg>';
    Quill.import('ui/icons')['table'] = TableIcon;

    const quill = new Quill(editorEl, {
        theme: 'snow',
        modules: {
            toolbar: {
                container: [
                    [{ header: [2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link', 'image', 'blockquote', 'table'],
                    ['clean'],
                ],
                handlers: {
                    table() {
                        const range = quill.getSelection(true);
                        const tableHtml = '<table><tbody><tr><td>Sel 1</td><td>Sel 2</td></tr><tr><td>Sel 3</td><td>Sel 4</td></tr></tbody></table><p><br></p>';
                        quill.clipboard.dangerouslyPasteHTML(range.index, tableHtml, 'user');
                    },
                },
            },
        },
    });

    const syncHiddenInput = () => {
        if (hiddenInput) {
            hiddenInput.value = quill.root.innerHTML;
        }
    };

    syncHiddenInput();
    quill.on('text-change', syncHiddenInput);
});
