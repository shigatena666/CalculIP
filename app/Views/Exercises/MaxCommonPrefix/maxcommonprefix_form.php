<form action="" method="post">
    <div class="form-group">
        <div class="input-group">
            <input type="text" name="ip" id="ip" class="form-control" placeholder="0.0.0.0" aria-label="Adresse reseau"/>
            <span class="input-group-addon">/</span>
            <input type="text" name="taille" id="taille" class="form-control" placeholder="0" aria-label="taille"/>
        </div>
    </div>
    <input type='hidden' name='adresse1' value=''/>
    <input type='hidden' name='adresse2' value=''/>
    <input type='hidden' name='ressemblance' value=''/>
    <div class="form-group">
        <div class="col-xs-12 col-md-4 col-md-offset-4">
            <input type="submit" name="submit" value="Valider" class="btn btn-success col-xs-12" />
        </div>
    </div>
</form>