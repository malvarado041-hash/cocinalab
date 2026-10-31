document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('receta-form');
    if (!form) return;
    const links = JSON.parse(document.getElementById('receta-vinculos').textContent);
    const wrap = document.getElementById('ings-wrap');
    const steps = document.getElementById('pasos-wrap');
    const yieldInput = document.getElementById('porciones');
    const priceInput = document.getElementById('precio-platillo');
    const hiddenProcedure = document.getElementById('procedimiento-hidden');
    const money = value => '$' + value.toFixed(2);
    let nextIngredient = wrap.children.length;
    let nextStep = steps.children.length;
    let previews = [];
    const unitsFor = unit => ({kg:['g','kg'], g:['g','kg'], l:['ml','L'], ml:['ml','L']}[unit?.toLowerCase()] || (unit ? [unit] : []));
    const factor = (from, to) => {
        from = from.toLowerCase(); to = to.toLowerCase();
        if (!from || !to) return null;
        if (from === to) return 1;
        for (const family of [{g:1,kg:1000}, {ml:1,l:1000}]) {
            if (family[from] && family[to]) return family[from]/family[to];
        }
        return null;
    };
    function configureIngredient(row, initial = false) {
        const select = row.querySelector('.ing-select');
        const unit = row.querySelector('.unidad-select');
        const product = links[select.value];
        const current = initial ? unit.dataset.current : '';
        const options = unitsFor(product?.unidad);
        unit.replaceChildren(new Option(product ? 'Selecciona…' : '—', ''));
        options.forEach(value => unit.add(new Option(value, value)));
        unit.disabled = !product;
        unit.value = options.includes(current) ? current : (product?.unidad || '');
        row.querySelector('.vinculo-info').textContent = product
            ? (product.precio === null ? 'Sin precio de referencia en almacén.' : `Referencia: ${money(Number(product.precio))} / ${product.unidad}`)
            : 'Selecciona un ingrediente para ver su precio de referencia.';
    }
    function initializeIngredient(row) {
        configureIngredient(row, true);
        const select = row.querySelector('.ing-select');
        const change = () => { configureIngredient(row); recalculate(); };
        if (window.TomSelect) {
            const ts = new TomSelect(select, {create:false, maxItems:1, placeholder:'Buscar ingrediente…',
                onInitialize() { this.control_input.setAttribute('aria-label','Buscar ingrediente del almacén'); },
                render: { no_results: () => '<div class="no-results">No hay productos activos con ese nombre.</div>' }
            });
            ts.on('change', change);
        } else select.addEventListener('change', change);
    }
    function recalculate() {
        let total = 0, complete = true, selected = 0;
        const used = new Set();
        [...wrap.children].forEach(row => {
            const select = row.querySelector('.ing-select');
            const product = links[select.value];
            const quantityInput = row.querySelector('.cantidad-input');
            const quantity = Number(quantityInput.value);
            const unit = row.querySelector('.unidad-select').value;
            const conversion = product ? factor(unit, product.unidad || '') : null;
            const duplicate = select.value && used.has(select.value);
            (select.tomselect?.control_input || select).setCustomValidity(duplicate ? 'Este ingrediente ya está agregado.' : '');
            if (select.value) { used.add(select.value); selected++; }
            const valid = !duplicate && product && quantity > 0 && conversion !== null && product.precio !== null;
            const subtotal = valid ? Math.round(quantity * conversion * Number(product.precio) * 100)/100 : null;
            if (subtotal !== null) total += subtotal; else complete = false;
            row.querySelector('.subtotal-line').textContent = duplicate ? 'Ingrediente repetido' : (subtotal === null ? 'Subtotal: —' : money(subtotal));
            row.querySelector('.cantidad-legacy').value = quantityInput.value ? `${quantityInput.value} ${unit}`.trim() : '';
            row.querySelector('.btn-remove-ing').disabled = wrap.children.length === 1;
        });
        const portions = Number(yieldInput.value);
        complete = complete && portions >= 1 && Number.isInteger(portions) && selected > 0;
        const perPortion = portions > 0 ? Math.round(total / portions * 100)/100 : 0;
        document.getElementById('ingredientes-count').textContent = `${selected} ingrediente${selected === 1 ? '' : 's'}`;
        document.getElementById('resumen-porciones').textContent = portions > 0 ? `${portions} porci${portions === 1 ? 'ón' : 'ones'}` : '—';
        document.getElementById('costo-receta').textContent = complete ? money(total) : '—';
        document.getElementById('gasto-total').textContent = complete ? money(perPortion) : '—';
        document.getElementById('gasto-nota').textContent = complete ? 'Estimado con los precios actuales del almacén.' : 'Completa ingredientes, cantidades y unidades para calcular el costo.';
        const margin = document.getElementById('margen-hint');
        margin.dataset.state = '';
        if (!complete) margin.textContent = 'Completa los ingredientes para calcular el margen.';
        else if (!priceInput.value) margin.textContent = 'Agrega un precio de venta para conocer el margen.';
        else {
            const price = Number(priceInput.value), profit = price - perPortion;
            margin.dataset.state = profit >= 0 ? 'positive' : 'negative';
            margin.textContent = `Margen por porción: ${money(profit)}${price > 0 ? ' · '+(profit/price*100).toFixed(1)+'%' : ''}${profit < 0 ? '. El precio no cubre el costo.' : ''}`;
        }
    }
    function cloneTemplate(id, index) {
        const template = document.getElementById(id);
        const fragment = template.content.cloneNode(true);
        fragment.querySelectorAll('[id], [for], [name], [data-idx]').forEach(el => {
            for (const key of ['id','for','name','data-idx']) {
                if (el.hasAttribute(key)) el.setAttribute(key, el.getAttribute(key).replaceAll('__INDEX__', index));
            }
        });
        return fragment;
    }
    function updateSteps() {
        [...steps.children].forEach((row,index) => {
            row.querySelector('.paso-num').textContent = index + 1;
            row.querySelector('.paso-input').setAttribute('aria-label',`Paso ${index+1}`);
            row.querySelector('.paso-up').disabled = index === 0;
            row.querySelector('.paso-down').disabled = index === steps.children.length-1;
            row.querySelector('.paso-del').disabled = steps.children.length === 1;
        });
        hiddenProcedure.value = [...steps.querySelectorAll('.paso-input')].map(el=>el.value.trim()).filter(Boolean).join('\n');
        if (hiddenProcedure.value.length >= 10) document.getElementById('pasos-error').hidden = true;
    }
    document.getElementById('btn-add-ing').addEventListener('click', () => {
        wrap.append(cloneTemplate('ingrediente-template', nextIngredient++));
        const row = wrap.lastElementChild;
        initializeIngredient(row); recalculate();
        const select = row.querySelector('.ing-select');
        if (select.tomselect) select.tomselect.focus(); else select.focus();
    });
    wrap.addEventListener('click', event => {
        const button = event.target.closest('.btn-remove-ing');
        if (!button || wrap.children.length === 1) return;
        const row = button.closest('.ing-row');
        row.querySelector('.ing-select').tomselect?.destroy(); row.remove(); recalculate();
    });
    wrap.addEventListener('input', recalculate);
    wrap.addEventListener('change', recalculate);
    yieldInput.addEventListener('input', recalculate);
    priceInput.addEventListener('input', recalculate);
    document.getElementById('btn-add-paso').addEventListener('click', () => {
        steps.append(cloneTemplate('paso-template',nextStep++)); updateSteps();
        steps.lastElementChild.querySelector('.paso-input').focus();
    });
    steps.addEventListener('input', updateSteps);
    steps.addEventListener('click', event => {
        const row = event.target.closest('.paso-row'); if (!row) return;
        if (event.target.closest('.paso-up') && row.previousElementSibling) steps.insertBefore(row,row.previousElementSibling);
        else if (event.target.closest('.paso-down') && row.nextElementSibling) steps.insertBefore(row.nextElementSibling,row);
        else if (event.target.closest('.paso-del') && steps.children.length > 1) row.remove();
        updateSteps();
    });
    const images = document.getElementById('receta-imagenes');
    images.addEventListener('change', () => {
        previews.forEach(URL.revokeObjectURL); previews = [];
        const area = document.getElementById('image-previews'); area.replaceChildren();
        const files = [...images.files];
        const error = files.length > 6 ? 'Selecciona un máximo de 6 imágenes.' : files.some(f=>f.size>2*1024*1024 || !['image/jpeg','image/png','image/webp'].includes(f.type)) ? 'Usa imágenes JPG, PNG o WebP de hasta 2 MB.' : '';
        images.setCustomValidity(error);
        document.getElementById('image-error').textContent = error;
        document.getElementById('image-error').hidden = !error;
        if (error) return;
        files.forEach((file,index) => {
            const figure = document.createElement('figure'), img = document.createElement('img'), caption = document.createElement('span');
            const url = URL.createObjectURL(file); previews.push(url);
            img.src = url; img.alt = `Vista previa ${index+1}`;
            caption.textContent = index === 0 ? 'Portada · '+file.name : file.name;
            figure.append(img,caption); area.append(figure);
        });
    });
    form.addEventListener('submit', event => {
        updateSteps(); recalculate();
        if (hiddenProcedure.value.length < 10) {
            event.preventDefault(); document.getElementById('pasos-error').hidden = false;
            steps.querySelector('.paso-input').focus(); return;
        }
        if (!form.checkValidity()) { event.preventDefault(); form.reportValidity(); return; }
        const button = document.getElementById('guardar-receta'); button.disabled = true; button.textContent = 'Guardando receta…';
    });
    [...wrap.children].forEach(initializeIngredient);
    updateSteps(); recalculate();
});
