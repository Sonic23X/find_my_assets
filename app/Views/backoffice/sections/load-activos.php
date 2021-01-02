            
            <div class="container-fluid mb-5"></div>
            <br>
            <div class="row">
                <div class="col">
                    <input type="button" value="Descargar excel de ejemplo" class="btn btn-primary btn-block mt-4">
                </div>
                <div class="col">
                    <h6 class="text-center">Cambiar Excel</h6>
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="excelFile" onChange="changeFile(this)" accept=".xlsx">
                            <label class="custom-file-label" for="excelFile" id="excelFileName"></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="alert alert-danger w-100" role="alert">
                        <ol id="errors">
                    
                        </ol>
                    </div>
                </div>
            </div>

