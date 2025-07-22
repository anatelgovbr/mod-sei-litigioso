# Módulo de Controle Litigioso

## Requisitos
- Requisito Mínimo é o SEI 4.1.5 instalado/atualizado - Não é compatível com versões anteriores e em versões mais recentes é necessário conferir antes se possui compatibilidade.
   - Verificar valor da constante de versão no arquivo /sei/web/SEI.php ou, após logado no sistema, parando o mouse sobre a logo do SEI no canto superior esquerdo.
- Antes de executar os scripts de instalação/atualização, o usuário de acesso aos bancos de dados do SEI e do SIP, constante nos arquivos ConfiguracaoSEI.php e ConfiguracaoSip.php, deverá ter permissão de acesso total ao banco de dados, permitindo, por exemplo, criação e exclusão de tabelas.
- Os códigos-fonte do Módulo podem ser baixados a partir do link a seguir, devendo sempre utilizar a versão mais recente: [https://github.com/anatelgovbr/mod-sei-litigioso/releases](https://github.com/anatelgovbr/mod-sei-litigioso/releases "Clique e acesse")
- Se já tiver instalado versão principal com a execução dos scripts de banco do módulo no SEI e no SIP, **em versões intermediárias basta sobrescrever os códigos** e não precisa executar os scripts de banco novamente.
   - Atualizações apenas de código são identificadas com o incremento apenas do terceiro dígito da versão (p. ex. v4.1.1, v4.1.2) e não envolve execução de scripts de banco.

## Procedimentos para Instalação
1. Fazer backup dos bancos de dados do SEI e do SIP.
2. Carregar no servidor os arquivos do módulo nas pastas correspondentes nos servidores do SEI e do SIP.
   - **Caso se trate de atualização de versão anterior do Módulo**, antes de copiar os códigos-fontes para a pasta "/sei/web/modulos/litigioso", é necessário excluir os arquivos anteriores pré existentes na mencionada pasta, para não manter arquivos de códigos que foram renomeados ou descontinuados.
3. Editar o arquivo "/sei/config/ConfiguracaoSEI.php", tomando o cuidado de usar editor que não altere o charset do arquivo, para adicionar a referência à classe de integração do módulo e seu caminho relativo dentro da pasta "/sei/web/modulos" na array 'Modulos' da chave 'SEI':

		'SEI' => array(
			...
			'Modulos' => array(
				'LitigiosoIntegracao' => 'litigioso',
				),
			),

4. Antes de seguir para os próximos passos, é importante conferir se o Módulo foi corretamente declarado no arquivo "/sei/config/ConfiguracaoSEI.php". Acesse o menu **Infra > Módulos** e confira se consta a linha correspondente ao Módulo, pois, realizando os passos anteriores da forma correta, independente da execução do script de banco, o Módulo já deve ser reconhecido na tela aberta pelo menu indicado.
5. Rodar o script de banco "/sip/scripts/sip_atualizar_versao_modulo_litigioso.php" em linha de comando no servidor do SIP, verificando se não houve erro em sua execução, em que ao final do log deverá ser informado "FIM". Exemplo de comando de execução:

		/usr/bin/php -c /etc/php.ini /opt/sip/scripts/sip_atualizar_versao_modulo_litigioso.php > atualizacao_modulo_litigioso_sip.log

6. Rodar o script de banco "/sei/scripts/sei_atualizar_versao_modulo_litigioso.php" em linha de comando no servidor do SEI, verificando se não houve erro em sua execução, em que ao final do log deverá ser informado "FIM". Exemplo de comando de execução:

		/usr/bin/php -c /etc/php.ini /opt/sei/scripts/sei_atualizar_versao_modulo_litigioso.php > atualizacao_modulo_litigioso_sei.log

7. **IMPORTANTE**: Na execução dos dois scripts de banco acima, ao final deve constar o termo "FIM", o "TEMPO TOTAL DE EXECUÇÃO" e a informação de que a instalação/atualização foi realizada com sucesso na base de dados correspondente (SEM ERROS). Do contrário, o script não foi executado até o final e algum dado não foi inserido/atualizado no respectivo banco de dados, devendo recuperar o backup do banco e repetir o procedimento.
   - Constando ao final da execução do script as informações indicadas, pode logar no SEI e SIP e verificar no menu **Infra > Parâmetros** dos dois sistemas se consta o parâmetro "VERSAO_MODULO_LITIGIOSO" com o valor da última versão do módulo.
8. Em caso de erro durante a execução do script, verificar (lendo as mensagens de erro e no menu Infra > Log do SEI e do SIP) se a causa é algum problema na infraestrutura local ou ajustes indevidos na estrutura de banco do core do sistema. Neste caso, após a correção, deve recuperar o backup do banco pertinente e repetir o procedimento, especialmente a execução dos scripts de banco indicados acima.
9. Após a execução com sucesso, com um usuário com permissão de Administrador no SEI, seguir os passos dispostos no tópico "Orientações Negociais" mais abaixo.
	
## Orientações Negociais
1. Imediatamente após a instalação com sucesso, com usuário com permissão de "Administrador" do SEI, acessar os menus de administração do módulo pelo seguinte caminho: Administração > Controle de Processo Litigiosos.
2. O script de banco do SIP já cria todos os Recursos e Menus e os associam automaticamente ao Perfil "Básico" ou ao Perfil "Administrador".
	- Independente da criação de outros Perfis, os recursos indicados para o Perfil "Básico" ou "Administrador" devem manter correspondência com os Perfis dos Usuários internos que utilizarão o Módulo e dos Usuários Administradores do Módulo.
	- Tão quanto ocorre com as atualizações do SEI, versões futuras deste Módulo continuarão a atualizar e criar Recursos e associá-los apenas aos Perfis "Básico" e "Administrador".
	- Todos os recursos do Módulo iniciam pelo prefixo "md_lit_".
3. O módulo possui as seguintes funcionalidades no menu de Administração:
	- Tipos de Controles Litigiosos: Cadastro dos tipos de controle litigioso existentes no órgão com suas Fases e Situações, incluindo os Dispositivos Normativos Associados e os Tipos de Decisão Associados.
		- É a tela principal de administração do módulo, onde cada tipo de controle litigioso é independente e pode atender todas as necessidades de controle de processos litigiosos que existam no órgão.
		- Na Anatel temos controle litigioso para processos de Sanção Finalísticos, de Sanção Contratual e de Sanção de Licitação.
	- Dispositivos Normativos: Cadastro dos dispositivos e condutas jurídicas que podem ser usados para vinculação da infração normativa ocorrida no processo.
	- Tipos de Decisão: Cadastro dos Tipos de Decisão, Espécies de Decisão e Obrigações que podem ser aplicadas nos casos em concreto, que serão usadas nas situações tipificadas como Decisória na parametrização das Fases e Situações do tipo de controle litigioso correspondente.
	- Lista de Serviços Outorgados: Cadastro manual ou po integração dos serviços outorgados pelo órgão que serão objeto do controle litigioso.
	- Mapeamento das Integrações: Cadastro e mapeamento dos Webservices que se integram com o módulo para fazer, por exemplo, a automação da gestão e cobrança de multas com o sistema de arrecadação.
	- Reincidências Específicas e Antecedentes: parametrizações gerais sobre Reincidências Específicas e Antecedentes, que são utilizadas sobre botões correspondentes no cadastro das Situações dos processos na seção de "Decisões".
	- Lista de Motivos para Instauração: Lista que pode ser utilizada no campo correspondente para associar Motivos de Instauração na tela de Administração do Tipo de Controle Litigioso.
		- Na tela de Administração do Tipo de Controle Litigioso **é opcional** associar Motivos de Instauração, mas, se se associar, nos processos será obrigatório indicar pelo menos um Motivo de Instauração.
		- Atenção para não confundir Motivos de Instauração com os Tipos de Processo. Caso achar pertinente, não utilizar essa opção.
	- Lista de Situações do Lançamento de Crédito: Utilizado somente se tiver integração por Webservice com Sistema de Arrecadação para gestão de multas.
		- Associa Situações do Lançamento com Situações do lado do Módulo e a Cor que o texto deve ser apresentado na seção de Gestão de Multa na marcação de Situações dos processos.
4. Para parametrização dos Tipos de Decisão, Espécies de Decisão e Obrigações, vide planilha de exemplo da parametrização da Anatel: https://docs.google.com/spreadsheets/d/e/2PACX-1vQ5HSkSiX8HnJvSUYMsURL3hciAX92vIqvTt-TOMCr0O6yaNog9FAgN7RYgF-NdD6Awv86tU7xB1r6d/pubhtml
5. Para parametrização dos Dispositivos Normativos e Condutas, vide planilha de exemplo da parametrização da Anatel: https://docs.google.com/spreadsheets/d/e/2PACX-1vT9mkpXBjvQbMcodB9q_bsuoLjcLwKNk9n2JSS199HXQybl56qKSkPyI-o6Pfs8kGWGqvMnZDDSJvuZ/pubhtml
6. A parametrização da Lista de Serviços Outorgados e do Mapeamento das Integrações é **opcional**, mas é DETERMINANTE para que o módulo possa fazer a gestão de multas e pagamentos relacionadas ao controle litigioso.

## Erros ou Sugestões
1. [Abrir Issue](https://github.com/anatelgovbr/mod-sei-litigioso/issues) no repositório do GitHub do módulo se ocorrer erro na execução dos scripts de banco do módulo no SEI ou no SIP acima.
2. [Abrir Issue](https://github.com/anatelgovbr/mod-sei-litigioso/issues) no repositório do GitHub do módulo se ocorrer erro na operação do módulo.
3. Na abertura da Issue utilizar o modelo **"1 - Reportar Erro"**.
