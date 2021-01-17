<?php

use Illuminate\Support\Facades\Route;


/**
 * Rotas Captacao
 */
Route::prefix('captacao')
        ->namespace('Captacao')
        ->middleware('auth')
        ->group(function () {

                /**
                 * Rotas Captações
                 */
                Route::any('captacao/search', 'CaptacaoController@search')->name('captacao.search');
                Route::resource('captacao', 'CaptacaoController');  

                Route::any('historico/create/{id_captacao}', 'HistoricoController@create')->name('historico.create');
                Route::any('historico/store', 'HistoricoController@store')->name('historico.store');
                Route::put('historico/update/{id_historico}', 'HistoricoController@update')->name('historico.update');
                Route::get('historico/delete/{id_avaliacao}', 'HistoricoController@destroy')->name('historico.destroy');

        }
);

/**
 * Rotas Financeiro
 */
Route::prefix('financeiro')
        ->namespace('Financeiro')
        ->middleware('auth')
        ->group(function () {

                /**
                 * Rotas Financeiro RECEBÍVEL
                 */                
                Route::get('relatorios', 'Relatorio\RecebivelRelatorioController@index')->name('recebiveis.relatorios.index')->middleware('can:Recebível Ver');
                Route::post('relatorios', 'Relatorio\RecebivelRelatorioController@recebiveis')->name('relatorios.recebiveis')->middleware('can:Recebível Ver');

                Route::resource('financeiro', 'FinanceiroController');
                route::any('alunos/{id_aluno}', 'FinanceiroController@indexAluno')->name('financeiro.indexAluno');
                Route::get('create/{id_aluno}', 'FinanceiroController@create')->name('financeiro.create');                
                Route::get('recebivel/destroy/{id_aluno}', 'FinanceiroController@destroy')->name('financeiro.destroy');   
                Route::any('recebivel/searchAluno', 'FinanceiroController@searchAluno')->name('recebivel.aluno.search');
                Route::any('recebivel/search', 'FinanceiroController@search')->name('recebivel.search');

                /**
                 * Rotas Financeiro RECEBIMENTO
                 */                
                Route::resource('recebimento', 'RecebimentoController');                
                Route::get('recebimento/create/{id_recebivel}', 'RecebimentoController@create')->name('recebimento.create');
                Route::get('recebimento/recibo/{id_recebivel}', 'RecebimentoController@recibo')->name('recebimento.recibo');
                Route::get('recebimento/destroy/{id_recebivel}', 'RecebimentoController@destroy')->name('recebimento.destroy');

                Route::resource('boleto', 'BoletoController');
                route::any('aluno/{id_aluno}', 'BoletoController@indexAluno')->name('boleto.indexAluno');
                Route::get('create/boleto/{id_aluno}', 'BoletoController@create')->name('boleto.create');     
                /* Route::post('store/boleto', 'BoletoController@store')->name('boleto.store');            */
                /*Route::get('recebivel/destroy/{id_aluno}', 'BoletoController@destroy')->name('boleto.destroy');   
                Route::any('recebivel/searchAluno', 'BoletoController@searchAluno')->name('recebivel.aluno.search');
                Route::any('recebivel/search', 'BoletoController@search')->name('recebivel.search'); */

        });
/**
 * Rotas Pedagogico
 */
Route::prefix('pedagogico')
        ->namespace('Pedagogico')
        ->middleware('auth')
        ->group(function () {

                /**
                 * Rotas Tipos turmas pedagógico
                 */
                Route::resource('tiposturmas', 'PedagogicoTipoTurmaController');

                /**
                 * Rotas Avaliações
                 */
                Route::get('tiposturmas/{id_tipo_turma}/avaliacoes/create', 'AvaliacaoController@create')->name('tiposturmas.avaliacao.create');
                route::get('tiposturmas/avaliacoes/{id_avaliacao}', 'AvaliacaoController@remover')->name('tiposturmas.avaliacoes.remover');
                Route::get('tiposturmas/avaliacoes/{id_avaliacao}/edit', 'AvaliacaoController@edit')->name('tiposturmas.avaliacoes.edit');
                Route::any('tiposturmas/avaliacoes/search', 'AvaliacaoController@search')->name('tiposturmas.avaliacoes.search');
                route::put('tiposturmas/avaliacoes/{id_avaliacao}', 'AvaliacaoController@update')->name('tiposturmas.avaliacoes.update');
                Route::delete('tiposturmas/avaliacoes/{id_avaliacao}', 'AvaliacaoController@destroy')->name('tiposturmas.avaliacoes.destroy');
                Route::get('tiposturmas/avaliacoes/{id_avaliacao}', 'AvaliacaoController@show')->name('tiposturmas.avaliacoes.show');
                Route::post('tiposturmas/avaliacoes', 'AvaliacaoController@store')->name('tiposturmas.avaliacoes.store');
                route::any('tiposturmas/{id}/avaliacoes', 'AvaliacaoController@index')->name('tiposturmas.avaliacoes');

                /**
                 * Rotas Tipos Frequencia
                 */
                Route::any('tiposfrequencias/search', 'TipoFrequenciaController@search')->name('tiposfrequencias.search');
                Route::resource('tiposfrequencias', 'TipoFrequenciaController');

                /**
                 * Rotas Tipos Avaliação
                 */
                Route::any('tiposavaliacoes/search', 'TipoAvaliacaoController@search')->name('tiposavaliacoes.search');
                Route::resource('tiposavaliacoes', 'TipoAvaliacaoController');

                /**
                 * Rotas turmas pedagógico
                 */
                //Diário - conteúdo lecionado e frequencia
                Route::any('turmas/search', 'PedagogicoTurmaController@search')->name('turmas.diarios.search')->middleware('can:Diário Ver');
                route::get('turmas', 'PedagogicoTurmaController@index')->name('turmas.diarios.index');

                /**
                 * Rotas Contéudos Lecionados
                 */
                route::get('turmas/conteudoslecionados/fichabranco', 'ConteudoLecionadoController@pdfFichaBranco')->name('turmas.conteudoslecionados.fichabranco');
                route::get('turmas/conteudolecionado/mensal', 'ConteudoLecionadoController@pdfMensal')->name('turmas.conteudoslecionados.mensal');
                route::get('turmas/conteudolecionado/{id_conteudo_lecionado}', 'ConteudoLecionadoController@remover')->name('turmas.conteudoslecionados.remover');
                route::put('turmas/conteudolecionado/{id_conteudo_lecionado}', 'ConteudoLecionadoController@update')->name('turmas.conteudoslecionados.update');
                Route::post('turmas/conteudoslecionados', 'ConteudoLecionadoController@store')->name('turmas.conteudoslecionados.store');
                route::any('turmas/{id}/conteudoslecionados', 'ConteudoLecionadoController@index')->name('turmas.conteudoslecionados')->middleware('can:Conteúdo Lecionado Ver');

                /**
                 * Rotas Frequências
                 */
                route::put('turmas/frequencia/{id_frequencia}', 'FrequenciaController@update')->name('turmas.frequencias.update');
                Route::get('turmas/frequencia/{id_frequencia}/edit', 'FrequenciaController@edit')->name('turmas.frequencia.edit');
                Route::get('turmas/{id_turma}/periodo/{id_periodo_letivo}/disciplina/{id_disciplina}/delete', 'FrequenciaController@delete')->name('turmas.frequencias.delete');
                Route::get('turmas/{id_turma}/data/{data_aula}/disciplina/{id_disciplina}/remove', 'FrequenciaController@remover')->name('turmas.frequencias.remover');
                Route::get('turmas/{id_turma_periodo_letivo}/frequencia/{id_matricula}/showaluno', 'FrequenciaController@frequenciaShowAluno')->name('turmas.frequencias.showaluno');
                Route::post('turmas/frequencias', 'FrequenciaController@store')->name('turmas.frequencias.store');
                route::any('turmas/{id}/frequencias', 'FrequenciaController@index')->name('turmas.frequencias')->middleware('can:Frequência Ver');

                /**
                 * Rotas Notas
                 */
                route::get('turmas/nota/{id_nota_avaliacao}', 'NotaController@remover')->name('turmas.nota.remover');               
                route::any('turmas/notas/updatenotasturma', 'NotaController@atualizarNotasTurma')->name('turmas.notas.updatenotasturma');
                route::put('turmas/nota/{id_nota_avaliacao}', 'NotaController@update')->name('turmas.notas.update');
                Route::get('turmas/nota/{id_nota_avaliacao}/edit', 'NotaController@edit')->name('turmas.nota.edit');
                Route::any('turmas/notas/search', 'NotaController@search')->name('turmas.notas.search');
                Route::get('turmas/nota/{id_matricula}/showaluno', 'NotaController@notaShowAluno')->name('turmas.notas.showaluno');
                Route::post('turmas/notas', 'NotaController@store')->name('turmas.notas.store');
                route::any('turmas/{id}/notas', 'NotaController@index')->name('turmas.notas');
                route::get('turmas/notas', 'NotaController@indexNotas')->name('turmas.index.notas')->middleware('can:Nota Ver');

                /**
                 * Rotas relatórios diários
                 */
                Route::get('turmas/relatorios/diario', 'Relatorio\DiarioController@diario')->name('turmas.relatorios.diarios');
                Route::post('turmas/relatorios/diario/filtros', 'Relatorio\DiarioController@filtros')->name('turmas.relatorios.diarios.filtros');
                Route::post('turmas/relatorios/diario/frequencia_branco', 'Relatorio\DiarioController@frequenciaFichaBranco')->name('turmas.relatorios.diarios.frequencia_branco');

                Route::get('frequencia/export/', 'Relatorio\DiarioController@frequenciaExcel')->name('frequencia.excel');

                /**
                 * Rotas recuperação Final
                */
                 /**
                 * Rotas Tipos Avaliação
                 */
                Route::any('recuperacaofinal/search', 'RecuperacaoFinalController@search')->name('recuperacaofinal.search');                
                Route::resource('recuperacaofinal', 'RecuperacaoFinalController');
                Route::get('recuperacaofinal/{id_recuperacao}/destroy', 'RecuperacaoFinalController@destroy')->name('recuperacaofinal.destroy');

                /**
                 * Rotas Resultado Final
                 */
                /*route::get('resultadofinal/nota/{id_nota_avaliacao}', 'ResultadoFinalController@remover')->name('resultadofinal.nota.remover');
                */
                route::put('resultadofinal/aluno/{id_nota_avaliacao}', 'ResultadoFinalController@update')->name('resultadofinal.update');
                Route::get('resultadofinal/aluno/{id_nota_avaliacao}/edit', 'ResultadoFinalController@edit')->name('resultadofinal.edit');
                Route::any('resultadofinal/result/search', 'ResultadoFinalController@search')->name('resultadofinal.search');                
                Route::post('resultadofinal/notas', 'ResultadoFinalController@store')->name('resultadofinal.store');
                route::any('resultadofinal/{id}/turmas', 'ResultadoFinalController@index')->name('resultadofinal.index');
                route::get('resultadofinal/turmas', 'ResultadoFinalController@indexResultadoFinal')->name('resultadofinal.index.turmas')->middleware('can:Resultado Final Ver');
        });

/**
 * Rotas Turmas X Período Letivo
 */
Route::get('turmas/periodosletivos/getPeriodosLetivos/{id}', 'Pedagogico\TurmaPeriodoLetivoController@getPeriodosLetivos')->name('turmas.periodosletivos.getPeriodosLetivos');
route::post('turmas/periodoletivo/{id_turma_periodo_letivo}/update', 'Pedagogico\TurmaPeriodoLetivoController@update')->name('turmas.periodosletivos.update');
route::post('turmas/{id}/periodosletivos', 'Pedagogico\TurmaPeriodoLetivoController@vincularPeriodosTurma')->name('turmas.periodoletivo.vincular');
route::any('turmas/{id}/periodosletivos/add', 'Pedagogico\TurmaPeriodoLetivoController@periodosLetivosAdd')->name('turmas.periodosletivos.add');
route::get('turmas/{id}/periodosletivos', 'Pedagogico\TurmaPeriodoLetivoController@periodosLetivos')->name('turmas.periodosletivos')->middleware('can:Período Letivo Ver');

/**
 * Autenticidade de documentos * 
 */
Route::get('autenticidade', 'Secretaria\DocumentoEscolaController@autenticidade')->name('matriculas.documentos_escola.autenticidade');
Route::any('verifica_autenticidade', 'Secretaria\DocumentoEscolaController@verificarAutenticidade')->name('matriculas.documentos_escola.verifica_autenticidade');

/**
 * Agendamento on line
 */
Route::get('agendamento', 'Captacao\CaptacaoController@createAgendamento')->name('agendamento');
Route::post('agendamento', 'Captacao\CaptacaoController@storeAgendamento')->name('agendamento.store');

/**
 * Rotas Secretaria
 */
Route::prefix('secretaria')
        ->namespace('Secretaria')
        ->middleware('auth')
        ->group(function () {

                /**
                 * Rotas pasta de matrículas
                 */
                //Pasta de Matrículas do aluno
                Route::any('matriculas/{id}/pasta', 'MatriculaPastaController@pasta')->name('matriculas.pasta');
                //Arquivo de matrículas do responsável
                Route::any('matriculas/{id}/arquivo', 'MatriculaPastaController@arquivo')->name('matriculas.arquivo');

                /**
                 * Rotas Check List Matrículas X Documentos
                 */
                route::get('matriculas/{id}/matricula/{id_matricula}/remove', 'MatriculaDocumentoController@removerDocumentosMatricula')->name('matriculas.documentos.remover');
                route::post('matriculas/{id}/documentos', 'MatriculaDocumentoController@vincularDocumentosMatricula')->name('matriculas.documentos.vincular');
                route::any('matriculas/{id}/documentos/add', 'MatriculaDocumentoController@documentosAdd')->name('matriculas.documentos.add');
                route::get('matriculas/{id}/documentos', 'MatriculaDocumentoController@documentos')->name('matriculas.documentos');

                /**
                 * Rotas geração documentos escola
                 * Declarações, etc
                 */
                Route::get('matriculas/documentos_escola', 'DocumentoEscolaController@create')->name('matriculas.documentos_escola.create');
                Route::get('matriculas/documentos_escola/{id_documento}', 'DocumentoEscolaController@show')->name('matriculas.documentos_escola.show');
                Route::get('matriculas/{id_aluno}/documentos_escola', 'DocumentoEscolaController@index')->name('matriculas.documentos_escola')->middleware('can:Matrícula Ver');
                Route::post('matriculas/documentos_escola', 'DocumentoEscolaController@gerar')->name('matriculas.documentos_escola.gerar');
                Route::get('matriculas/documentos_escola/{id_documento}/invalidar/{id_aluno}', 'DocumentoEscolaController@invalidarDocumento')->name('matriculas.documentos_escola.invalidar');
                Route::get('matriculas/documentos_escola/{id_documento}/revalidar/{id_aluno}', 'DocumentoEscolaController@revalidarDocumento')->name('matriculas.documentos_escola.revalidar');

                /**
                 * Rotas Matrículas
                 */
                //geração de senha para todos usuários
                /* Route::any('matriculas/gerarSenhaTodos', 'MatriculaController@gerarUserTodos')->name('matriculas.gerarusertodos'); */                

                Route::get('matriculas/create/{id_turma}', 'MatriculaController@create')->name('matriculas.create');
                Route::put('matriculas/{id_turma}', 'MatriculaController@update')->name('matriculas.update');
                Route::put('matriculas/{id_turma}/updateTurma', 'MatriculaController@updateTurma')->name('matriculas.update.turma');
                Route::get('matriculas/{id_turma}/edit', 'MatriculaController@edit')->name('matriculas.edit');
                Route::get('matriculas/{id_turma}/editTurma', 'MatriculaController@editTurma')->name('matriculas.edit.turma');
                Route::any('matriculas/search', 'MatriculaController@search')->name('matriculas.search');
                Route::delete('matriculas/{id_turma}', 'MatriculaController@destroy')->name('matriculas.destroy');
                Route::get('matriculas/{id_turma}', 'MatriculaController@show')->name('matriculas.show');
                Route::post('matriculas', 'MatriculaController@store')->name('matriculas.store');
                Route::any('{id_turma}/matriculas', 'MatriculaController@index')->name('matriculas.index')->middleware('can:Matrícula Ver');

                Route::get('matriculas/requerimento/{id_matricula}', 'MatriculaController@imprimirReqMatricula')->name('matriculas.requerimento')->middleware('can:Matrícula Contrato Ver');
                Route::get('matriculas/contrato/{id_matricula}', 'MatriculaController@imprimirContrato')->name('matriculas.contrato')->middleware('can:Matrícula Contrato Ver');
                Route::get('matriculas/ficha/{id_aluno}', 'MatriculaController@imprimirFichaMatricula')->name('matriculas.ficha')->middleware('can:Matrícula Ver');
                Route::get('matriculas/getAlunos/{id_ano_letivo}', 'MatriculaController@getAlunos')->name('matriculas.getAlunos');                                
                Route::get('matriculas/getAlunosTurma/{id_turma}', 'MatriculaController@getAlunosTurma')->name('matriculas.getAlunosTurma');                
                Route::get('matriculas/getValores/{id_matricula}', 'MatriculaController@getValores')->name('matriculas.getValores');
                
                /**
                 * Rotas Atividades Extracurriculares
                 */
                /* Route::get('extracurriculares/getTurmas/{id}', 'ExtraCurricularController@getTurmas')->name('turmas.getTurmas'); */
                Route::any('extracurriculares/search', 'ExtraCurricularController@search')->name('extracurriculares.search');
                Route::resource('extracurriculares', 'ExtraCurricularController');

                /**
                 * Rotas Contrato atividades extracurriculares
                 */
                Route::get('contratos_extracurriculares/create/{id_matricula}', 'ContratoExtraCurricularController@create')->name('contratos.extracurricular.create');
                Route::post('contratos_extracurriculares/{id_contrato_extra}', 'ContratoExtraCurricularController@update')->name('contratos.extracurricular.update');                
                Route::get('contratos_extracurriculares/{id_contrato_extra}/edit', 'ContratoExtraCurricularController@edit')->name('contratos.extracurricular.edit');                
                /*Route::any('contratos_extracurriculares/search/contrato_extra', 'ContratoExtraCurricularController@search')->name('contratos.extracurricular.search');
                Route::delete('contratos_extracurriculares/{id_contrato_extra}', 'ContratoExtraCurricularController@destroy')->name('contratos.extracurricular.destroy');
                Route::get('contratos_extracurriculares/{id_contrato_extra}', 'ContratoExtraCurricularController@show')->name('contratos.extracurricular.show');*/
                Route::post('contratos_extracurriculares', 'ContratoExtraCurricularController@store')->name('contratos.extracurricular.store');
                /*Route::any('contratos_extracurriculares/{id_contrato_extra}/contratos', 'ContratoExtraCurricularController@index')->name('contratos.extracurricular.index')->middleware('can:Matrícula Contrato Ver'); */
                 
                /**
                 * Rotas Turmas
                 */
                Route::get('turmas/getTurmas/{id}', 'TurmaController@getTurmas')->name('turmas.getTurmas');
                Route::any('turmas/search', 'TurmaController@search')->name('turmas.search');
                Route::resource('turmas', 'TurmaController');

                /**
                 * Rotas Turmas X disciplina x professor
                 */                
                Route::post('turmas.professor', 'TurmaProfessorController@store')->name('turmasprofessor.store');
                Route::put('turmasprofessor/{id_turma_professor}', 'TurmaProfessorController@update')->name('turmasprofessor.update');
                Route::get('turmas/{id_turma_professor}/professor', 'TurmaProfessorController@edit')->name('turmasprofessor.edit');     
                Route::get('turmasprofessor/{id_turma}', 'TurmaProfessorController@index')->name('turmasprofessor')->middleware('can:Professor X Disciplina Ver');                       
                  
                /*
                *Rotas Pessoas
                */
                Route::any('pessoas/gerarLogin/{id_user}', 'PessoaController@gerarLogin')->name('pessoas.gerarlogin');
                Route::get('pessoas/create/aluno', 'PessoaController@create')->name('pessoas.create.aluno');
                Route::get('pessoas/create/responsavel', 'PessoaController@create')->name('pessoas.create.responsavel');
                Route::put('pessoas/{id_pessoa}', 'PessoaController@update')->name('pessoas.update');
                Route::get('pessoas/{id_pessoa}/edit', 'PessoaController@edit')->name('pessoas.edit');
                Route::any('pessoas/search', 'PessoaController@search')->name('pessoas.search');
                Route::delete('pessoas/{id_pessoa}', 'PessoaController@destroy')->name('pessoas.destroy');
                Route::get('pessoas/{id_pessoa}', 'PessoaController@show')->name('pessoas.show');
                Route::post('pessoas', 'PessoaController@store')->name('pessoas.store');
                Route::any('{id_tipo_pessoa}/pessoas', 'PessoaController@index')->name('pessoas.index')->middleware('can:Pessoa Ver');
                Route::get('pessoas/getPessoa/{nome}', 'PessoaController@getPessoa')->name('pessoas.getPessoa');
                Route::get('pessoas/getResponsaveisTodos/{resp}', 'PessoaController@getResponsaveisTodos')->name('pessoas.getResponsaveisTodos');                

                /**
                 * Rotas disciplinas
                 */
                Route::any('disciplinas/search', 'DisciplinaController@search')->name('disciplinas.search');
                Route::resource('disciplinas', 'DisciplinaController');

                Route::get('/', 'DisciplinaController@index')->name('secretaria.index');

                /**
                 * Rotas disciplinas
                 */
                Route::any('disciplinas/search', 'DisciplinaController@search')->name('disciplinas.search');
                Route::resource('disciplinas', 'DisciplinaController');

                Route::get('/', 'DisciplinaController@index')->name('secretaria.index');
                
                /**
                 * Rotas Opção Educacional1
                */                
                Route::any('opcaoeducacional/search', 'OpcaoEducacionalController@search')->name('opcaoeducacional.search');
                Route::get('opcaoeducacional/create', 'OpcaoEducacionalController@create')->name('opcaoeducacional.create');
                Route::get('opcaoeducacional/create/resp', 'OpcaoEducacionalController@createResponsavel')->name('opcaoeducacional.create.resp');
                route::get('opcaoeducacional/{id_opcao}/delete', 'OpcaoEducacionalController@destroy')->name('opcaoeducacional.destroy');
                Route::put('opcaoeducacional/{id_opcao}', 'OpcaoEducacionalController@update')->name('opcaoeducacional.update');
                route::get('opcaoeducacional/{id_opcao}', 'OpcaoEducacionalController@edit')->name('opcaoeducacional.edit');
                route::get('opcaoeducacional/resp/{id_opcao}', 'OpcaoEducacionalController@editResponsavel')->name('opcaoeducacional.edit.resp');
                Route::post('opcaoeducacional/store', 'OpcaoEducacionalController@store')->name('opcaoeducacional.store');
                route::any('opcaoeducacional', 'OpcaoEducacionalController@index')->name('opcaoeducacional.index');
                Route::get('opcaoeducacional/print/{id_opcao}', 'OpcaoEducacionalController@imprimir')->name('opcaoeducacional.print');
                Route::any('opcaoeducacional/responsavel/index', 'OpcaoEducacionalController@indexResponsavel')->name('opcaoeducacional.responsavel');

                /**
                 * Rotas relatórios secretaria
                 */
                Route::get('relatorios', 'Relatorio\SecretariaController@index')->name('secretaria.relatorios.index')->middleware('can:Pessoa Ver');
                Route::post('relatorios/filtros', 'Relatorio\SecretariaController@filtros')->name('secretaria.relatorios.filtros')->middleware('can:Pessoa Ver');
        });

/**
 * Rotas Admin
 */
Route::prefix('admin')
        ->namespace('Admin')
        ->middleware('auth')
        ->group(function () {

                /**
                 * Rotas Tipos Documentos Identidade
                 */
                Route::any('tiposdocidentidade/search', 'TipoDocIdentidadeController@search')->name('tiposdocidentidade.search');
                Route::resource('tiposdocidentidade', 'TipoDocIdentidadeController');

                /**
                 * Rotas Tipos Desconto Curso
                 */
                Route::any('descontoscursos/search', 'DescontoCursoController@search')->name('descontoscursos.search');
                Route::resource('descontoscursos', 'DescontoCursoController');

                /**
                 * Rotas Atendimento Especializado
                 */
                Route::any('atendimentosespecializados/search', 'AtendimentoEspecializadoController@search')->name('atendimentosespecializados.search');
                Route::resource('atendimentosespecializados', 'AtendimentoEspecializadoController');

                /**
                 * Rotas Grade curricular
                 * Turmas X Disciplinas
                 */
                Route::get('tiposturmas/getDisciplinas/{id}', 'GradeCurricular\GradeCurricularController@getDisciplinas')->name('tiposturmas.getdisciplinas');

                Route::get('tiposturmas/{id}/disciplina/{id_disciplina}/remove', 'GradeCurricular\GradeCurricularController@removerDisciplinasTurma')->name('tiposturmas.disciplinas.remover');
                Route::post('tiposturmas/disciplinas/{id}/update', 'GradeCurricular\GradeCurricularController@updateCargaHoraria')->name('tiposturmas.disciplinas.update');
                Route::post('tiposturmas/{id}/disciplinas', 'GradeCurricular\GradeCurricularController@vincularDisciplinasTurma')->name('tiposturmas.disciplinas.vincular');

                Route::any('tiposturmas/{id}/disciplinas/add', 'GradeCurricular\GradeCurricularController@disciplinasAdd')->name('tiposturmas.disciplinas.add');
                Route::get('tiposturmas/{id}/disciplinas', 'GradeCurricular\GradeCurricularController@disciplinas')->name('tiposturmas.disciplinas');

                /**
                 * Rotas Tipos Turmas
                 */
                Route::any('tiposturmas/search', 'TipoTurmaController@search')->name('tiposturmas.search');
                Route::resource('tiposturmas', 'TipoTurmaController')->middleware('can:Padrão Turma Ver');

                /**
                 * Rotas Docs Matrícula
                 */
                Route::any('tiposdocumentos/search', 'TipoDocumentoController@search')->name('tiposdocumentos.search');
                Route::resource('tiposdocumentos', 'TipoDocumentoController');

                /**
                 * Rotas períodos letivos
                 */
                Route::get('periodosletivos/getPeriodos/{id}', 'PeriodoLetivoController@getPeriodos')->name('periodos.getPeriodos');
                Route::any('periodosletivos/search', 'PeriodoLetivoController@search')->name('periodosletivos.search');
                Route::resource('periodosletivos', 'PeriodoLetivoController')->middleware('can:Período Letivo Ver');

                /**
                 * Rotas anos letivos
                 */
                Route::any('anosletivos/search', 'AnoLetivoController@search')->name('anosletivos.search');
                Route::resource('anosletivos', 'AnoLetivoController')->middleware('can:Ano Letivo Ver');
                Route::get('anosletivos/getAnosLetivos/', 'AnoLetivoController@getAnosLetivos')->name('anosletivos.getAnosLetivos');

                /**
                 * Rotas users
                 */
                Route::any('users/search', 'ACL\UserController@search')->name('users.search');
                Route::get('users/editsenha', 'ACL\UserController@editSenha')->name('users.editsenha');                
                Route::put('users/update/senha', 'ACL\UserController@updateSenha')->name('users.updatesenha');
                Route::resource('users', 'ACL\UserController');

                /**
                 * Rotas Usuários X Unidades Ensino
                 */
                route::get('users/{id}/unidadeensino/{id_unidade_ensino}/remove', 'ACL\UserUnidadeEnsinoController@removerUnidadesUser')->name('users.unidadesensino.remover');
                route::post('users/unidadeensino/{id_usuario_unidade_ensino}/update', 'ACL\UserUnidadeEnsinoController@update')->name('users.unidadesensino.update');
                route::post('users/{id}/unidadesensino', 'ACL\UserUnidadeEnsinoController@vincularUnidadesUser')->name('users.unidadesensino.vincular');
                route::any('users/{id}/unidadesensino/add', 'ACL\UserUnidadeEnsinoController@unidadesEnsinoAdd')->name('users.unidadesensino.add');
                route::get('users/{id}/unidadesensino', 'ACL\UserUnidadeEnsinoController@unidadesEnsino')->name('users.unidadesensino');

                /**
                 * Rotas Perfis X Permissões
                 */
                route::get('perfis/{id}/permissao/{id_permissao}/remove', 'ACL\PerfilPermissaoController@removerPermissoesPerfil')->name('perfis.permissoes.remover');
                route::post('perfis/{id}/permissoes', 'ACL\PerfilPermissaoController@vincularPermissoesPerfil')->name('perfis.permissoes.vincular');
                route::any('perfis/{id}/permissoes/add', 'ACL\PerfilPermissaoController@permissoesAdd')->name('perfis.permissoes.add');
                route::get('perfis/{id}/permissoes', 'ACL\PerfilPermissaoController@permissoes')->name('perfis.permissoes');

                /**
                 * Rotas permissões
                 */
                Route::any('permissoes/search', 'ACL\PermissaoController@search')->name('permissoes.search');
                Route::resource('permissoes', 'ACL\PermissaoController')->middleware('can:Permissão Ver');

                /**
                 * Rotas perfis
                 */
                Route::any('perfis/search', 'ACL\PerfilController@search')->name('perfis.search');
                Route::resource('perfis', 'ACL\PerfilController')->middleware('can:Perfil Ver');

                /*
            *Rotas Unidades de ensino
             */
                Route::get('UnidadesEnsino/create', 'UnidadeEnsinoController@create')->name('unidadesensino.create');
                Route::get('UnidadesEnsino/define', 'UnidadeEnsinoController@unidadeDefinida')->name('unidadeensino.definida');
                Route::put('UnidadesEnsino/{id_unidade_ensino}', 'UnidadeEnsinoController@update')->name('unidadesensino.update');
                Route::get('UnidadesEnsino/{id_unidade_ensino}/edit', 'UnidadeEnsinoController@edit')->name('unidadesensino.edit');
                Route::any('UnidadesEnsino/search', 'UnidadeEnsinoController@search')->name('unidadesensino.search');
                Route::delete('UnidadesEnsino/{id_unidade_ensino}', 'UnidadeEnsinoController@destroy')->name('unidadesensino.destroy');
                Route::get('UnidadesEnsino/{id_unidade_ensino}', 'UnidadeEnsinoController@show')->name('unidadesensino.show');
                Route::post('UnidadesEnsino', 'UnidadeEnsinoController@store')->name('unidadesensino.store');
                Route::get('UnidadesEnsino', 'UnidadeEnsinoController@index')->name('unidadesensino.index')->middleware('can:Unidade Ensino Ver');

                //Route::get('/', 'UnidadeEnsinoController@index')->name('admin.index');

        });

Route::get('/', function () {
        return view('welcome');
});

/**
 * Geração de PDF
 */
Route::get('pdf', 'PdfController@gerarPdf');

/**
 * Principal
 */
Route::get('/', 'Principal\PrincipalController@index')->name('home');
Route::put('/', 'Principal\PrincipalController@defineUnidadePadrao')->name('home.define');

//Auth::routes(); 
//desabilita link para auto registro de usuário
Auth::routes(['register' => false]);

/**
 * Captcha
 */
Route::get('contact-form', 'CaptchaServiceController@index');
Route::post('captcha-validation', 'CaptchaServiceController@capthcaFormValidate');
Route::get('reload-captcha', 'CaptchaServiceController@reloadCaptcha');