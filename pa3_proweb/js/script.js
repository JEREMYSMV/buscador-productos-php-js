const API_PRODUCTOS = "api/products/index.php";
const API_FILTRAR = "api/products/filtrar.php";
const API_CATEGORIAS = "api/categorias/index.php";

// REQ 3: Estado de la paginación
let paginaActual = 1;
let productosPorPagina = 10;
let filtrosActuales = {};
let solicitudProductos = 0;

// Navegación
document.getElementById("menu-home").addEventListener("click", (event) => {
    event.preventDefault();
    showHome();
});

document.getElementById("menu-product").addEventListener("click", (event) => {
    event.preventDefault();
    showProductPage();
});

showHome();

// Página de inicio
function showHome() {
    solicitudProductos++;

    fetch("views/home.html")
        .then(response => {
            if (!response.ok) {
                throw new Error("No se pudo cargar la vista de inicio");
            }
            return response.text();
        })
        .then(html => {
            document.getElementById("div_content").innerHTML = html;

            document.getElementById("btn-products")
                ?.addEventListener("click", showProductPage);
        })
        .catch(error => {
            showError("Ocurrió un error al cargar la página de inicio.", error);
        });
}

// Página de productos
function showProductPage() {
    solicitudProductos++;

    fetch("views/view_product.html")
        .then(response => {
            if (!response.ok) {
                throw new Error("No se pudo cargar la vista de productos");
            }
            return response.text();
        })
        .then(html => {
            document.getElementById("div_content").innerHTML = html;

            // Reiniciar paginación
            paginaActual = 1;
            productosPorPagina = 10;
            filtrosActuales = {};

            cargarCategorias();
            initFiltros();
            initPaginacion();
            cargarProductos();
        })
        .catch(error => {
            showError("Ocurrió un error al cargar la página de productos.", error);
        });
}

// REQ 3: Consultar productos con filtros y paginación
async function cargarProductos() {
    const contenedor = document.getElementById("products-container");
    const mensaje = document.getElementById("filtros-mensaje");
    const info = document.getElementById("info-paginacion");
    const controles = document.getElementById("controles-paginacion");

    if (!contenedor) return;

    const solicitudActual = ++solicitudProductos;

    contenedor.innerHTML =
        '<p class="text-slate-500">Cargando productos...</p>';

    if (info) info.textContent = "";
    if (controles) controles.innerHTML = "";

    const parametros = {
        ...filtrosActuales,
        pagina: paginaActual,
        por_pagina: productosPorPagina
    };

    try {
        const response = await fetch(API_FILTRAR, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(parametros)
        });

        const data = await response.json();

        if (solicitudActual !== solicitudProductos ||
            !contenedor.isConnected) return;

        if (!response.ok || data.status !== true) {
            const detalle = Array.isArray(data.errores)
                ? " " + data.errores.join(" ")
                : "";

            throw new Error(
                (data.message || "No se pudieron cargar los productos.") +
                detalle
            );
        }

        if (!Array.isArray(data.productos) || !data.paginacion) {
            throw new Error("La respuesta de la API no es válida.");
        }

        renderProductos(
            data.productos,
            "Ningún producto cumple con los filtros seleccionados."
        );

        renderPaginacion(data.paginacion);

        if (mensaje) {
            mensaje.textContent =
                `${data.paginacion.total_registros} producto(s) encontrado(s).`;
        }

    } catch (error) {
        if (solicitudActual !== solicitudProductos ||
            !contenedor.isConnected) return;

        contenedor.innerHTML = `
            <p class="rounded-lg bg-red-100 p-4 text-red-700">
                ${escapar(error.message)}
            </p>
        `;

        console.error(error);
    }
}

// Mostrar productos
function renderProductos(productos, mensajeVacio) {
    const contenedor = document.getElementById("products-container");

    if (!contenedor) return;

    contenedor.innerHTML = productos.length
        ? productos.map(productoCard).join("")
        : `<p class="text-slate-500">${escapar(mensajeVacio)}</p>`;

    contenedor.querySelectorAll("[data-product-id]").forEach(button => {
        button.addEventListener("click", () => {
            verDetalle(button.dataset.productId);
        });
    });
}

// Cargar categorías
async function cargarCategorias() {
    const select = document.getElementById("f-categoria");

    if (!select) return;

    try {
        const response = await fetch(API_CATEGORIAS);
        const categorias = await response.json();

        if (!response.ok || !Array.isArray(categorias)) {
            throw new Error("Categorías no disponibles");
        }

        if (!select.isConnected) return;

        categorias.forEach(categoria => {
            select.add(new Option(categoria.title, categoria.id));
        });

    } catch (error) {
        console.error(error);
    }
}

// Inicializar filtros
function initFiltros() {
    const form = document.getElementById("form-filtros");
    const mensaje = document.getElementById("filtros-mensaje");

    if (!form) return;

    form.addEventListener("submit", (event) => {
        event.preventDefault();

        filtrosActuales = {};

        new FormData(form).forEach((valor, campo) => {
            if (String(valor).trim() !== "") {
                filtrosActuales[campo] = String(valor).trim();
            }
        });

        // Al filtrar, regresar a la primera página
        paginaActual = 1;

        if (mensaje) mensaje.textContent = "";

        ocultarDetalle();
        cargarProductos();
    });

    document.getElementById("btn-limpiar")
        .addEventListener("click", () => {
            form.reset();

            filtrosActuales = {};
            paginaActual = 1;

            if (mensaje) mensaje.textContent = "";

            ocultarDetalle();
            cargarProductos();
        });
}

// REQ 3: Selector de cantidad de productos
function initPaginacion() {
    const selector = document.getElementById("por-pagina");

    if (!selector) return;

    selector.value = String(productosPorPagina);

    selector.addEventListener("change", () => {
        productosPorPagina = Number(selector.value);

        // Regresar a la primera página
        paginaActual = 1;

        ocultarDetalle();
        cargarProductos();
    });
}

// REQ 3: Crear botones de paginación
function renderPaginacion(paginacion) {
    const contenedor = document.getElementById("controles-paginacion");
    const info = document.getElementById("info-paginacion");

    if (!contenedor || !info) return;

    const totalPaginas = Number(paginacion.total_paginas);
    const totalRegistros = Number(paginacion.total_registros);

    paginaActual = Number(paginacion.pagina_actual);

    if (totalRegistros === 0) {
        info.textContent = "No se encontraron productos.";
        contenedor.innerHTML = "";
        return;
    }

    const inicio = (paginaActual - 1) * productosPorPagina + 1;
    const fin = Math.min(
        paginaActual * productosPorPagina,
        totalRegistros
    );

    info.textContent =
        `Mostrando ${inicio}-${fin} de ${totalRegistros} productos`;

    contenedor.innerHTML = "";

    // Botón anterior
    crearBotonPaginacion(
        contenedor,
        "Anterior",
        paginaActual - 1,
        paginaActual === 1
    );

    // Números de página
    for (let i = 1; i <= totalPaginas; i++) {
        crearBotonPaginacion(
            contenedor,
            String(i),
            i,
            false,
            i === paginaActual
        );
    }

    // Botón siguiente
    crearBotonPaginacion(
        contenedor,
        "Siguiente",
        paginaActual + 1,
        paginaActual === totalPaginas
    );
}

// REQ 3: Crear cada botón
function crearBotonPaginacion(
    contenedor,
    texto,
    pagina,
    deshabilitado = false,
    activo = false
) {
    const boton = document.createElement("button");

    boton.type = "button";
    boton.textContent = texto;
    boton.disabled = deshabilitado;

    boton.className = activo
        ? "rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white"
        : "rounded-lg border border-slate-300 bg-white px-4 py-2 text-slate-700 hover:bg-blue-100";

    if (deshabilitado) {
        boton.classList.add("opacity-40", "cursor-not-allowed");
    }

    if (activo) {
        boton.setAttribute("aria-current", "page");
    }

    boton.addEventListener("click", () => {
        if (deshabilitado || activo) return;

        paginaActual = pagina;

        ocultarDetalle();
        cargarProductos();

        document.getElementById("form-filtros")
            ?.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
    });

    contenedor.appendChild(boton);
}

// Ocultar detalle al cambiar de página
function ocultarDetalle() {
    const detalle = document.getElementById("product-detail");

    if (detalle) {
        detalle.classList.add("hidden");
    }
}

// Crear tarjeta de producto
function productoCard(p) {
    const imagen = Array.isArray(p.imagenes) && p.imagenes.length
        ? p.imagenes[0]
        : "";

    return `
        <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            ${imagen
                ? `<img src="${escapar(imagen)}"
                        alt="${escapar(p.nombre)}"
                        class="h-52 w-full bg-white object-contain p-4"
                        loading="lazy">`
                : '<div class="flex h-52 items-center justify-center bg-slate-100 text-slate-400">Sin imagen</div>'
            }

            <div class="p-4">

                <p class="mb-1 text-xs uppercase tracking-wide text-blue-600">
                    ${escapar(p.categoria || "Sin categoría")}
                </p>

                <h3 class="line-clamp-2 min-h-12 font-semibold text-slate-800">
                    ${escapar(p.nombre)}
                </h3>

                <p class="mt-2 text-xl font-bold text-slate-900">
                    S/ ${Number(p.precio).toFixed(2)}
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Stock: ${Number(p.stock ?? 0)}
                </p>

                <button data-product-id="${Number(p.id)}"
                    class="mt-4 w-full rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">
                    Ver detalle
                </button>

            </div>
        </article>
    `;
}

// Consultar detalle del producto
async function verDetalle(id) {
    try {
        const response = await fetch(
            `${API_PRODUCTOS}?id=${encodeURIComponent(id)}`
        );

        const p = await response.json();

        if (!response.ok) {
            throw new Error(p.message || "No se encontró el producto");
        }

        const imagenes = Array.isArray(p.imagenes) ? p.imagenes : [];
        const detalle = document.getElementById("product-detail");

        if (!detalle) return;

        detalle.innerHTML = `
            <div class="grid gap-6 md:grid-cols-2">

                <div>
                    ${imagenes.length
                        ? imagenes.map(url => `
                            <img src="${escapar(url)}"
                                 alt="${escapar(p.nombre)}"
                                 class="mx-auto max-h-96 w-full object-contain">
                        `).join("")
                        : '<div class="p-8 text-slate-400">Sin imagen disponible</div>'
                    }
                </div>

                <div>
                    <p class="text-sm uppercase tracking-wide text-blue-600">
                        ${escapar(p.categoria || "Sin categoría")}
                    </p>

                    <h3 class="mt-2 text-2xl font-bold text-slate-800">
                        ${escapar(p.nombre)}
                    </h3>

                    <p class="mt-3 text-2xl font-bold">
                        S/ ${Number(p.precio).toFixed(2)}
                    </p>

                    <p class="mt-4 leading-relaxed text-slate-600">
                        ${escapar(p.descripcion)}
                    </p>

                    <p class="mt-3 text-sm text-slate-500">
                        ID: ${Number(p.id)} ·
                        Categoría ID: ${Number(p.categoria_id)} ·
                        Stock: ${Number(p.stock ?? 0)}
                    </p>
                </div>

            </div>
        `;

        detalle.classList.remove("hidden");

        detalle.scrollIntoView({
            behavior: "smooth",
            block: "start"
        });

    } catch (error) {
        alert(error.message || "No se pudo cargar el detalle del producto.");
        console.error(error);
    }
}

// Evitar insertar HTML desde datos externos
function escapar(valor) {
    return String(valor ?? "").replace(/[&<>"']/g, caracter => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;"
    }[caracter]));
}

// Mostrar errores
function showError(mensaje, error) {
    document.getElementById("div_content").innerHTML = `
        <p class="rounded-lg bg-red-100 p-4 text-red-700">
            ${escapar(mensaje)}
        </p>
    `;

    console.error(error);
}