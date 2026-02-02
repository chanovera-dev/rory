jQuery(document).ready(function ($) {
    const $container = $('#ajax-posts-container');
    const $loadMoreBtn = $('#load-more-btn');
    const $btnText = $loadMoreBtn.find('.btn-text');
    const $btnLoader = $loadMoreBtn.find('.btn-loader');

    // Estado inicial
    let selectedCats = []; // Array Selección Múltiple
    let showNsfw = false;
    let isLoading = false;

    // Recuperar página inicial del botón (si existe)
    let currentPage = parseInt($loadMoreBtn.data('page')) || 1;

    // --- 1. Evento: Cambio de Categoría (Selección Múltiple) ---
    $('.cat-filter-btn').on('click', function (e) {
        e.preventDefault();

        const catId = $(this).data('cat-id');
        const isAll = (catId === 0);

        if (isAll) {
            // Si click en "Todos": Limpiar array y activar solo "Todos"
            selectedCats = [];
            $('.cat-filter-btn').removeClass('active');
            $('.cat-filter-btn[data-cat-id="0"]').addClass('active');
        } else {
            // Si click en categoría específica
            $('.cat-filter-btn[data-cat-id="0"]').removeClass('active');

            const index = selectedCats.indexOf(catId);
            if (index > -1) {
                // Quitar
                selectedCats.splice(index, 1);
                $(this).removeClass('active');
            } else {
                // Añadir
                selectedCats.push(catId);
                $(this).addClass('active');
            }

            if (selectedCats.length === 0) {
                $('.cat-filter-btn[data-cat-id="0"]').addClass('active');
            }
        }

        currentPage = 1;
        loadPosts(false);
    });

    // --- 2. Evento: Toggle NSFW ---
    $('#nsfw-toggle-input').on('change', function () {
        showNsfw = $(this).is(':checked');
        currentPage = 1;
        loadPosts(false);
    });

    // --- 3. Evento: Botón Cargar Más ---
    $loadMoreBtn.on('click', function (e) {
        e.preventDefault();
        if (!isLoading) {
            currentPage++;
            loadPosts(true);
        }
    });

    /**
     * Función principal de carga AJAX
     * @param {boolean} isAppend - true para paginación, false para filtros
     */
    function loadPosts(isAppend) {
        if (isLoading) return;
        isLoading = true;

        if (isAppend) {
            // UI Carga Paginación
            $loadMoreBtn.prop('disabled', true);
            $btnText.hide();
            $btnLoader.show();
        } else {
            // UI Carga Filtro Global
            $container.css('opacity', '0.5');
            $loadMoreBtn.hide(); // Ocultar paginación mientras filtramos
        }

        $.ajax({
            url: rory_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'rory_filter_posts',
                nonce: rory_ajax.nonce,
                categories: selectedCats, // Enviamos el array
                nsfw: showNsfw,
                paged: currentPage
            },
            success: function (res) {
                if (res.success) {
                    if (isAppend) {
                        $container.append(res.data.html);
                    } else {
                        $container.html(res.data.html);
                    }

                    // Actualizar etiquetas de año
                    setTimeout(updateYearLabels, 150); // Pequeño delay para asegurar que el grid se asentó

                    // Gestión del Botón Ver Más
                    const maxPages = parseInt(res.data.max_pages);
                    $loadMoreBtn.data('page', currentPage);

                    if (currentPage < maxPages) {
                        $loadMoreBtn.show().prop('disabled', false);
                        $btnText.show();
                        $btnLoader.hide();
                    } else {
                        $loadMoreBtn.hide();
                    }

                } else {
                    if (!isAppend) {
                        $container.html('<div class="no-results">' + res.data.message + '</div>');
                        $loadMoreBtn.hide();
                    } else {
                        $loadMoreBtn.hide();
                    }
                }
            },
            error: function () {
                if (!isAppend) {
                    $container.html('<div class="no-results">Error de conexión. Inténtalo de nuevo.</div>');
                }
            },
            complete: function () {
                isLoading = false;
                if (!isAppend) {
                    $container.css('opacity', '1');
                } else {
                    $loadMoreBtn.prop('disabled', false);
                    $btnText.show();
                    $btnLoader.hide();
                }
            }
        });
    }

    /**
     * Genera etiquetas flotantes para los años
     */
    function updateYearLabels() {
        // Eliminar etiquetas existentes que no sean del loader o after
        $container.find('.year-float-label').remove();

        let lastYear = null;
        let lastTop = -1;
        const $items = $container.find('.ajax-item-wrapper');

        $items.each(function () {
            const currentYear = $(this).data('year');
            const pos = $(this).position();

            if (currentYear && currentYear !== lastYear) {
                // Es el primer item de este año
                let top = pos.top;

                // Evitar que etiquetas de distintos años se pisen si empiezan en la misma fila
                if (Math.abs(top - lastTop) < 20) {
                    top += 40;
                }

                const $label = $('<div class="year-float-label">— ' + currentYear + '</div>');

                // Posicionar relativo al item
                $label.css({
                    'top': top + 'px',
                    'opacity': 1
                });

                $container.append($label);
                lastYear = currentYear;
                lastTop = top;
            }
        });
    }

    // Inicializar etiquetas al cargar
    updateYearLabels();

    // Actualizar al redimensionar la ventana
    let resizeTimer;
    $(window).on('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(updateYearLabels, 250);
    });
});
