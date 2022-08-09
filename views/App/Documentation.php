<?php $this->layout('Components::template', ['page_title' => 'API Documentation']) ?>

<div class="container mw-1100 py-4">
    <div class="row">
        <div class="col-md-12">
            <div id="swagger-ui"></div>
            
            <script>
                window.onload = function() {
                    const ui = SwaggerUIBundle({
                        url: "/api/v1/swagger",
                        dom_id: '#swagger-ui',
                        deepLinking: true
                    });

                    window.ui = ui;
                };
            </script>
        </div>
    </div>
</div>