
    <div class="row m-5 p-5">
        <div class="col-sm-3">
            <input type="number" placeholder="Precio de lista" id="monto" class="form-control">
        </div>
        <div class="col-sm-2">
            <select name="" id="tipo-medida" class="custom-select">
                <option value="" class="d-none">Tipo de medida</option>
                <option value="1">Meses</option>
                <option value="2">Años</option>
            </select>
        </div>
        <div class="col-sm-2">
            <select name="" id="tipo-activo" class="custom-select" disabled>
                <option value="" class="d-none">Tipo de activo</option>
                <option value="1" selected>Computo</option>
            </select>
        </div>
        <div class="col-sm-2">
            <input type="number" placeholder="Vida util estimada" id="vida-util" class="form-control">
        </div>
        <div class="col-sm-3">
            <input type="number" placeholder="Valor residual" id="residual" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
            <button class="btn btn-primary btn-block" onclick="calcular()">Calcular</button>
        </div>
        <div class="col-sm-1"></div>
    </div>

    <div class="row mt-5">
        <div class="col-sm-12 text-center">
            <span id="result"></span>
        </div>
    </div>