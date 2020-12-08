
@extends('templates.principal')

@section('title') Atender Solicitações @endsection

@section('content')
    <div style="border-bottom: #949494 2px solid; padding-bottom: 5px; margin-bottom: 10px">
      <h2>RETIRAR SOLICITAÇÕES</h2>
    </div>

    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <strong>{{session('success')}}</strong>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <table id="tableSolicitacoes" class="table table-hover table-responsive-md" style="margin-top: 10px;">
        <thead style="background-color: #151631; color: white; border-radius: 15px">
            <tr>
                <th scope="col">Requerente</th>
                <th scope="col">Material</th>
                <th scope="col">Situação</th>
                <th scope="col" style="text-align: center">Data</th>
                <th scope="col" style="text-align: center">Cancelar ou Entregar</th>
            </tr>
        </thead>
        <tbody>
            @if (count($dados) > 0 && count($materiaisPreview) > 0)
                @for ($i = 0; $i < count($dados); $i++)
                    <tr data-id="{{ $dados[$i]->solicitacao_id }}" style="cursor: pointer">
                        <td class="expandeOption" style="text-align: center">{{ $dados[$i]->nome }}</td>
                        <td class="expandeOption">{{$materiaisPreview[$i]}}...</td>
                        <td class="expandeOption">
                            @if ($dados[$i]->status == "Aprovado")
                                <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-check-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path fill-rule="evenodd" d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.236.236 0 0 1 .02-.022z"/>
                                </svg>
                            @endif
                            @if ($dados[$i]->status == "Aprovado Parcialmente")
                                <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-check2-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                    <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                                </svg>
                            @endif
                            {{ $dados[$i]->status }}
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"/>
                            </svg>
                        </td>
                        <td class="expandeOption" style="text-align: center">{{ date('d/m/Y',  strtotime($dados[$i]->created_at))}}</td>
                        <td class="expandeOption" style="text-align: center">
                            <button type="button" class="btn btn-danger cancelaEntregaSolicitacao" data-id="{{ $dados[$i]->solicitacao_id }}">
                                Cancelar
                            </button>
                            <button type="button" style="margin-left: 10px" class="btn btn-success entregaSolicitacao" data-id="{{ $dados[$i]->solicitacao_id }}">
                                Entregue
                            </button>
                        </td>
                    </tr>
                @endfor
            @endif
        </tbody>
    </table>

    <div class="modal fade" id="detalhesSolicitacao" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalLabel" style="color:#151631">Solicitação - <span id="numSolicitacao"></span></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div id="overlay">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-primary" style="width: 5rem; height: 5rem" role="status"></div>
                    </div>
                </div>
                <div id="modalBody" style="display: none">
                    <table id="tableItens" class="table table-hover table-responsive-md" style="margin-top: 10px">
                        <thead style="background-color: #151631; color: white; border-radius: 15px">
                            <tr>
                                <th scope="col">Material</th>
                                <th scope="col">Descrição</th>
                                <th scope="col" style="text-align: center; width: 10%">Qtd. Solicitada</th>
                                <th scope="col" style="text-align: center; width: 10%">Qtd. Aprovada</th>
                            </tr>
                        </thead>
                        <tbody id="listaItens"></tbody>
                    </table>
                    <div id="observacaoRequerente">
                        <label for="textObservacaoRequerente"><strong>Observações do Requerente:</strong></label>
                        <textarea class="form-control" name="observacaoRequerente" id="textObservacaoRequerente" cols="30" rows="3" readonly></textarea>
                    </div>
                    <div id="observacaoAdmin" style="margin-top: 10px">
                        <label for="textObservacaoAdmin"><strong>Observações do Administrador:</strong></label>
                        <textarea class="form-control" name="observacaoAdmin" id="textObservacaoAdmin" cols="30" rows="3" readonly></textarea>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
<script type="text/javascript" src="{{asset('js/solicitacoes/retira.js')}}"></script>