<div class="modal fade" id="modalTiming" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content p-4">

      <!---HEADER--->
      <div class="modal-header mb-4">
        <h1 class="fw-bold" style="color: #05072e; font-size:xx-large;">Timing</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!----CONTENEDOR DONDE PINTAREMOS INFORMACION--->
      <div id="timing-container"></div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", async () => {
    const el = document.getElementById("timing-container");

    // Estilo global del modal (inline, sin CSS externo)
    const modalContent = document.querySelector("#modalTiming .modal-content");
    if (modalContent) {
      modalContent.style.fontFamily =
        'system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif';
      modalContent.style.color = "#0b1220"; // negro suave
    }

    // util: escapar HTML
    const escapeHtml = (s) =>
      (s ?? "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");

    // Divide la descripción en líneas y separa [rango][texto]
    function descToLines(desc) {
      if (!desc) return [];
      let s = String(desc).replace(/\r\n/g, "\n").trim();
      // añade salto antes de cada "HH:MM - HH:MM"
      s = s.replace(/(\d{1,2}:\d{2}\s*-\s*\d{1,2}:\d{2})/g, "\n$1").replace(/^\n+/, "");
      const raw = s.split("\n").map(l => l.trim()).filter(Boolean);

      return raw.map(line => {
        const m = line.match(/^(\d{1,2}:\d{2}\s*-\s*\d{1,2}:\d{2})(.*)$/);
        if (m) return {
          range: m[1].trim(),
          text: (m[2] || "").trim()
        };
        return {
          range: null,
          text: line
        };
      });
    }

    // Estilos inline reutilizables
    const listStyle = "margin:8px 0 0 1.25rem;padding:0;";
    const itemStyle = "font-size:17px;line-height:1.55;margin:8px 0;color:#0b1220;";
    const rangeStyle = "font-weight:700;color:#000;white-space:nowrap;margin-right:4px;color: #05072e;";
    const textStyle = "color:#0b1220;";

    try {
      const res = await fetch("{{ route('cargarDatos.timing') }}", {
        headers: {
          "Accept": "application/json"
        }
      });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json();

      // Render con viñeta por línea y horas en negrita/negro
      el.innerHTML = data.timings.map(t => {
        const lines = descToLines(t.descripcion);
        const items = lines.map(l => {
          const rangeHtml = l.range ? `<span style="${rangeStyle}">${escapeHtml(l.range)}</span>` : "";
          const textHtml = l.text ? `<span style="${textStyle}">${escapeHtml(l.text)}</span>` : "";
          return `<li style="${itemStyle}">${rangeHtml}${textHtml}</li>`;
        }).join("");

        return `<ul style="${listStyle}">${items || `<li style="${itemStyle};color:#666;">(Sin descripción)</li>`}</ul>`;
      }).join("");
    } catch (e) {
      console.error(e);
      el.innerHTML = "<p style='color:#b00020'>Fallo de red o JSON inválido.</p>";
    }
  });
</script>