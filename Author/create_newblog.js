
document.addEventListener("DOMContentLoaded", function () {
    const fileUpload = document.getElementById("fileUpload");
    const previewImage = document.getElementById("previewImage");
    const content = document.getElementById("content");
    const preview = document.getElementById("preview");
    const hiddenContent = document.getElementById("format");

    const formattingMap = {};

    // Image Preview
    fileUpload.onchange = e => {
        previewImage.src = URL.createObjectURL(fileUpload.files[0]);
    };

    function replaceHTML(text) {
        return text.replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;");
    }

    function check(a, b) {
        return a.length === b.length && a.every(v => b.includes(v));
    }

    // Apply or remove text fomat style 
    function format(style) {
        const start = content.selectionStart, end = content.selectionEnd;
        if (start === end) return;

        let allHaveStyle = true;
        for (let i = start; i < end; i++) {
            const styles = formattingMap[i] || new Set();
            if (!styles.has(style)) {
                allHaveStyle = false;
                break;
            }
        }

        for (let i = start; i < end; i++) {
            formattingMap[i] = formattingMap[i] || new Set();
            if (allHaveStyle) {
                formattingMap[i].delete(style);
                if (formattingMap[i].size === 0) delete formattingMap[i];
            } else {
                formattingMap[i].add(style);
            }
        }

        updatePreview();
        updateHidden();
    }

    // Convertion
    function textConv(isForPreview = false) {
        const text = content.value;
        let result = '', i = 0;

        result += '<p>';

        while (i < text.length) {
            const styles = formattingMap[i] ? [...formattingMap[i]] : [];

            if (styles.length) {
                let open = '';
                let close = '';

                // Showed on previewe
                if (isForPreview) {
                    open = '<span style="';
                    if (styles.includes("bold")) open += 'font-weight:bold;';
                    if (styles.includes("italic")) open += 'font-style:italic;';
                    if (styles.includes("underline")) open += 'text-decoration:underline;';
                    open += '">';

                    close = '</span>';
                } else {
                    // Html tags saved on database
                    if (styles.includes("bold")) {
                        open += '<strong>';
                        close = '</strong>' + close;
                    }
                    if (styles.includes("italic")) {
                        open += '<em>';
                        close = '</em>' + close;
                    }
                    if (styles.includes("underline")) {
                        open += '<u>';
                        close = '</u>' + close;
                    }
                }

                result += open;
                while (i < text.length && check([...formattingMap[i] || []], styles)) {
                    if (text[i] === '\n') {
                        if (text[i + 1] === '\n') {
                            result += close + '</p><p>' + open;
                            i += 2;
                        } else {
                            result += '<br>';
                            i++;
                        }
                    } else {
                        result += replaceHTML(text[i++]);
                    }
                }
                result += close;
            } else {
                if (text[i] === '\n') {
                    if (text[i + 1] === '\n\n') {
                        result += '</p><p>';
                        i += 2;
                    } else {
                        result += '<br>';
                        i++;
                    }
                } else {
                    result += replaceHTML(text[i++]);
                }
            }
        }

        result += '</p>';
        return result;
    }

    function updatePreview() {
        preview.innerHTML = textConv(true);
    }

    function updateHidden() {
        hiddenContent.value = textConv(false);
    }

    ["bold", "italic", "underline"].forEach(style => {
        document.getElementById(style).addEventListener("click", () => format(style));
    });

    content.addEventListener("input", () => {
        updatePreview();
        updateHidden();
    });

    document.getElementById("publish-form").addEventListener("submit", updateHidden);

    updatePreview();
    updateHidden();
});