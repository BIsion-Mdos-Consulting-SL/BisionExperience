<div class="modal fade" id="qrModal-{{ $evento->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Código QR — {{ $evento->nombre }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body text-center">
                <!--VBISUALIZACION DEL QR-->
                <div class="d-inline-block p-3 mb-3">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(220)->margin(1)->generate($registroUrl) !!}
                </div>
            </div>
        </div>
    </div>
</div>