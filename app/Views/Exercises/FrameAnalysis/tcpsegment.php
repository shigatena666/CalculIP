<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" href="#toCollapseTcp">
        <h3 class="panel-title">Si Segment TCP : <i>Cliquez pour afficher/masquer</i></h3>
    </div>
    <div class="panel-body collapse" id="toCollapseTcp">
        <table class="col-xs-12 col-md-12 table">
            <tr>
                <td>
                    <div class="col-xs-6 col-md-6">
                        <div class="col-xs-12 col-md-12 input-group" id="portSTCP">
                            <div class="input-group-addon">Port source : </div>
                            <input type="text" class="form-control" placeholder="XXXX" name="TCPsourcePort">
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6">
                        <div class="col-xs-12 col-md-12 input-group" id="portDTCP">
                            <div class="input-group-addon">Port destination : </div>
                            <input type="text" class="form-control" placeholder="XXXX" name="TCPdestinationPort">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="col-xs-6 col-md-6">
                        <div class="col-xs-12 col-md-12 input-group" id="numSeq">
                            <div class="input-group-addon">Numéro de séquence : </div>
                            <input type="text" class="form-control" placeholder="XXXXXXXX" name="TCPsequenceNumber">
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6">
                        <div class="col-xs-12 col-md-12 input-group" id="numAcq">
                            <div class="input-group-addon">Numéro d'acquittement : </div>
                            <input type="text" class="form-control" placeholder="XXXXXXXX" name="TCPackNumber">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="col-xs-3 col-md-3">
                        <div class="col-xs-12 col-md-12 input-group" id="longEntete">
                            <div class="input-group-addon">Long. entête : </div>
                            <input type="text" class="form-control" placeholder="X" name="TCPheaderLength">
                        </div>
                    </div>
                    <div class="col-xs-3 col-md-3">
                        <div class="col-xs-12 col-md-12 input-group" id="000">
                            <div class="input-group-addon">000 : </div>
                            <input type="text" class="form-control" placeholder="bbb" name="TCPzeros">
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-6">
                        <div class="col-xs-12 col-md-12 input-group" id="drapeaux">
                            <div class="input-group-addon">Drapeaux : </div>
                            <input type="text" class="form-control" placeholder="bXX" name="TCPflags">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="col-xs-4 col-md-4">
                        <div class="col-xs-12 col-md-12 input-group" id="tailleFen">
                            <div class="input-group-addon">TailleFenêtre : </div>
                            <input type="text" class="form-control" placeholder="XXXX" name="TCPwindowLength">
                        </div>
                    </div>
                    <div class="col-xs-4 col-md-4">
                        <div class="col-xs-12 col-md-12 input-group" id="checksumTCP">
                            <div class="input-group-addon">Checksum : </div>
                            <input type="text" class="form-control" placeholder="XXXX" name="TCPchecksum">
                        </div>
                    </div>
                    <div class="col-xs-4 col-md-4">
                        <div class="col-xs-12 col-md-12 input-group" id="pointeur">
                            <div class="input-group-addon">Pointeur urgent : </div>
                            <input type="text" class="form-control" placeholder="XXXX" name="TCPpointer">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="panel panel-default">
                        <div class="panel-heading" data-toggle="collapse" href="#toCollapseDTcp">
                            <h3 class="panel-title">Drapeaux TCP : <i>Cliquez pour afficher/masquer</i></h3>
                        </div>
                        <div class="panel-body collapse" id="toCollapseDTcp">
                            <table class="col-xs-12 col-md-12 table">
                                <tr>
                                    <td>
                                        <div class="col-xs-2 col-md-2">
                                            <div class="col-xs-12 col-md-12 input-group" id="ns">
                                                <div class="input-group-addon">NS : </div>
                                                <input type="text" class="form-control" placeholder="b" name="TCPflagNs">
                                            </div>
                                        </div>
                                        <div class="col-xs-2 col-md-2">
                                            <div class="col-xs-12 col-md-12 input-group" id="cwr">
                                                <div class="input-group-addon">CWR : </div>
                                                <input type="text" class="form-control" placeholder="b" name="TCPflagCwr">
                                            </div>
                                        </div>
                                        <div class="col-xs-2 col-md-2">
                                            <div class="col-xs-12 col-md-12 input-group" id="ece">
                                                <div class="input-group-addon">ECE : </div>
                                                <input type="text" class="form-control" placeholder="b" name="TCPflagEce">
                                            </div>
                                        </div>
                                        <div class="col-xs-2 col-md-2">
                                            <div class="col-xs-12 col-md-12 input-group" id="urg">
                                                <div class="input-group-addon">URG : </div>
                                                <input type="text" class="form-control" placeholder="b" name="TCPflagUrgent">
                                            </div>
                                        </div>
                                        <div class="col-xs-2 col-md-2">
                                            <div class="col-xs-12 col-md-12 input-group" id="ack">
                                                <div class="input-group-addon">ACK : </div>
                                                <input type="text" class="form-control" placeholder="b" name="TCPflagAck">
                                            </div>
                                        </div>
                                        <div class="col-xs-2 col-md-2">
                                            <div class="col-xs-12 col-md-12 input-group" id="psh">
                                                <div class="input-group-addon">PSH : </div>
                                                <input type="text" class="form-control" placeholder="b" name="TCPflagPsh">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="col-xs-2 col-md-2">
                                            <div class="col-xs-12 col-md-12 input-group" id="rst">
                                                <div class="input-group-addon">RST : </div>
                                                <input type="text" class="form-control" placeholder="b" name="TCPflagRst">
                                            </div>
                                        </div>
                                        <div class="col-xs-2 col-md-2">
                                            <div class="col-xs-12 col-md-12 input-group" id="syn">
                                                <div class="input-group-addon">SYN : </div>
                                                <input type="text" class="form-control" placeholder="b" name="TCPflagSyn">
                                            </div>
                                        </div>
                                        <div class="col-xs-2 col-md-2">
                                            <div class="col-xs-12 col-md-12 input-group" id="fin">
                                                <div class="input-group-addon">FIN : </div>
                                                <input type="text" class="form-control" placeholder="b" name="TCPflagFin">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>