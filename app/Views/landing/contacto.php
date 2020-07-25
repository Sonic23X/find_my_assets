

    <div class="container-fluid" id="contactenos">

      <div class="row">
        <div class="col-sm-7"></div>
        <div class="col-sm-5">
          <p class="lead text-white text-center">
            <h3 class="text-white text-center"> ¿NECESITAS AYUDA? </h3>
            <span class="text-white text-center">Escríbenos, en un abrir y cerrar de ojos estaremos en contacto</span>
          </p>
          <form id="send-contact-email" action="<?= base_url( '/contacto' ) ?>">
            <div class="form-group">
              <input type="text" id="nombre" class="form-control form-control-lg" placeholder="Nombre">
            </div>
            <div class="form-group">
              <input type="email" id="email" class="form-control form-control-lg" placeholder="Correo electrónico">
            </div>
            <div class="form-group">
              <input type="number" id="phone" class="form-control form-control-lg" placeholder="Teléfono">
            </div>
            <div class="form-group">
              <textarea id="comentario" class="form-control form-control-lg" rows="3" placeholder="Escribe aquí tu mensaje"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Enviar</button>
          </form>
        </div>
      </div>

    </div>
