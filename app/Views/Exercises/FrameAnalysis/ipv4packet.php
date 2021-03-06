<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" href="#toCollapseIpv4">
        <h3 class="panel-title">Si Paquet IPv4 : <i>Cliquez pour afficher/masquer</i></h3>
    </div>
    <div class="panel-body collapse" id="toCollapseIpv4">
        <table class="col-xs-12 col-md-12 table">
            <tr>
                <td>
                    <div class="col-xs-3 col-md-3">
                        <div class="col-xs-12 col-md-12 input-group" id="numIP">
                            <div class="input-group-addon">N° ver. IP : </div>
                            <input type="text" class="form-control" placeholder="X" name="IPv4version">
                        </div>
                    </div>
                    <div class="col-xs-3 col-md-3">
                        <div class="col-xs-12 col-md-12 input-group"id="longEnteteIP">
                            <div class="input-group-addon">Long. entête : </div>
                            <input type="text" class="form-control" placeholder="X" name="IPv4headerLength">
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6">
                        <div class="col-xs-12 col-md-12 input-group" id="diffserv">
                            <div class="input-group-addon">DiffServ : </div>
                            <input type="text" class="form-control" placeholder="XX" name="IPv4serviceType">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="col-xs-6 col-md-6">
                        <div class="col-xs-12 col-md-12 input-group" id="longTotIP">
                            <div class="input-group-addon">Long. tot. en octets : </div>
                            <input type="text" class="form-control" placeholder="XXXX" name="IPv4totalLength">
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6">
                        <div class="col-xs-12 col-md-12 input-group" id="identification">
                            <div class="input-group-addon">Identification : </div>
                            <input type="text" class="form-control" placeholder="XXXX" name="IPv4identification">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="col-xs-2 col-md-2">
                        <div class="col-xs-12 col-md-12 input-group" id="0">
                            <div class="input-group-addon">0 : </div>
                            <input type="text" class="form-control" placeholder="b" name="IPv4zero">
                        </div>
                    </div>
                    <div class="col-xs-2 col-md-2">
                        <div class="col-xs-12 col-md-12 input-group" id="df">
                            <div class="input-group-addon">DF : </div>
                            <input type="text" class="form-control" placeholder="b" name="IPv4dontFragment">
                        </div>
                    </div>
                    <div class="col-xs-2 col-md-2">
                        <div class="col-xs-12 col-md-12 input-group" id="mf">
                            <div class="input-group-addon">MF : </div>
                            <input type="text" class="form-control" placeholder="b" name="IPv4moreFragment">
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6">
                        <div class="col-xs-12 col-md-12 input-group" id="offset">
                            <div class="input-group-addon">Offset : </div>
                            <input type="text" class="form-control" placeholder="bXXX" name="IPv4offset">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="col-xs-3 col-md-3">
                        <div class="col-xs-12 col-md-12 input-group" id="ttl">
                            <div class="input-group-addon">TTL : </div>
                            <input type="text" class="form-control" placeholder="XX" name="IPv4ttl">
                        </div>
                    </div>
                    <div class="col-xs-3 col-md-3">
                        <div class="col-xs-12 col-md-12 input-group" id="protocole">
                            <div class="input-group-addon">Protocole : </div>
                            <input type="text" class="form-control" placeholder="XX" name="IPv4protocol">
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6">
                        <div class="col-xs-12 col-md-12 input-group" id="hc">
                            <div class="input-group-addon">Header Checksum : </div>
                            <input type="text" class="form-control" placeholder="XXXX" name="IPv4headerChecksum">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="col-xs-6 col-md-6">
                        <div class="col-xs-12 col-md-12 input-group" id="ipE">
                            <div class="input-group-addon">Adresse IP émetteur : </div>
                            <input type="text" class="form-control" placeholder="D.D.D.D" name="IPv4Emitter">
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6">
                        <div class="col-xs-12 col-md-12 input-group" id="ipD">
                            <div class="input-group-addon">Adresse IP destinataire : </div>
                            <input type="text" class="form-control" placeholder="D.D.D.D" name="IPv4Receiver">
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>