# Módulo de Controle Litigioso

## Requisitos
- SEI 3.0.10 instalado/atualizado ou versão superior.
   - Verificar valor da constante de versão no arquivo /sei/web/SEI.php ou, após logado no sistema, parando o mouse sobre a logo do SEI no canto superior esquerdo.
- Antes de executar os scripts de instalação/atualização (itens 4 e 5 abaixo), o usuário de acesso aos bancos de dados do SEI e do SIP, constante nos arquivos ConfiguracaoSEI.php e ConfiguracaoSip.php, deverá ter permissão de acesso total ao banco de dados, permitindo, por exemplo, criação e exclusão de tabelas.

## Procedimentos para Instalação
1. Antes, fazer backup dos bancos de dados do SEI e do SIP.
2. Carregar no servidor os arquivos do módulo localizados na pasta "/sei/web/modulos/litigioso" e os scripts de instalação/atualização "/sip/scripts/sip_atualizar_versao_modulo_litigioso.php" e "/sei/scripts/sei_atualizar_versao_modulo_litigioso.php".
   - Caso se trate de atualização de versão anterior do Módulo, antes de copiar os códigos-fontes para a pasta "/sei/web/modulos/litigioso", é necessário excluir os arquivos anteriores pré existentes na mencionada pasta, para não manter arquivos de códigos que foram renomeados ou descontinuados.
3. Editar o arquivo "/sei/config/ConfiguracaoSEI.php", tomando o cuidado de usar editor que não altere o charset do arquivo, para adicionar a referência à classe de integração do módulo e seu caminho relativo dentro da pasta "/sei/web/modulos" na array 'Modulos' da chave 'SEI':

		'SEI' => array(
			'URL' => 'http://[Servidor_PHP]/sei',
			'Producao' => false,
			'RepositorioArquivos' => '/var/sei/arquivos',
			'Modulos' => array('LitigiosoIntegracao' => 'litigioso',)
			),

4. Rodar o script de banco "/sip/scripts/sip_atualizar_versao_modulo_litigioso.php" em linha de comando no servidor do SIP, verificando se não houve erro em sua execução, em que ao final do log deverá ser informado "FIM". Exemplo de comando de execução:

		/usr/bin/php -c /etc/php.ini /opt/sip/scripts/sip_atualizar_versao_modulo_litigioso.php > atualizacao_modulo_litigioso_sip.log
			
5. Rodar o script de banco "/sei/scripts/sei_atualizar_versao_modulo_litigioso.php" em linha de comando no servidor do SEI, verificando se não houve erro em sua execução, em que ao final do log deverá ser informado "FIM". Exemplo de comando de execução:

		/usr/bin/php -c /etc/php.ini /opt/sei/scripts/sei_atualizar_versao_modulo_litigioso.php > atualizacao_modulo_litigioso_sei.log

6. Após a execução com sucesso, com um usuário com permissão de Administrador no SEI, seguir os passos dispostos no tópico "Orientações Negociais" mais abaixo.
7. **IMPORTANTE**: Na execução dos dois scripts acima, ao final deve constar o termo "FIM" e informação de que a instalação ocorreu com sucesso (SEM ERROS). Do contrário, o script não foi executado até o final e algum dado não foi inserido/atualizado no banco de dados correspondente, devendo recuperar o backup do banco pertinente e repetir o procedimento.
   - Constando o termo "FIM" e informação de que a instalação ocorreu com sucesso, pode logar no SEI e SIP e verificar no menu Infra > Parâmetros dos dois sistemas se consta o parâmetro "VERSAO_MODULO_LITIGIOSO" com o valor da última versão do módulo.
8. Em caso de erro durante a execução do script, verificar (lendo as mensagens de erro e no menu Infra > Log do SEI e do SIP) se a causa é algum problema na infraestrutura local ou ajustes indevidos na estrutura de banco do core do sistema. Neste caso, após a correção, deve recuperar o backup do banco pertinente e repetir o procedimento, especialmente a execução dos scripts indicados nos itens 4 e 5 acima.
	- Caso não seja possível identificar a causa, entrar em contato com: Nei Jobson - neijobson@anatel.gov.br
	
## Orientações Negociais

1. Imediatamente após a instalação com sucesso, com usuário com permissão de "Administrador" do SEI, acessar os menus de administração do módulo pelo seguinte caminho: Administração > Controle de Processo Litigiosos.

2. O módulo possui os seguintes comandos no menu de administração que levam às seguintes funções:
	- Tipos de Controles Litigiosos: Cadastro, parametrização e individualização dos "fluxos" de situações para cada caso de controle litigioso. É a tela principal de administração do módulo.
	- Dispositivos Normativos: Cadastro dos dispositivos e condutas jurídicas que podem ser usados para vinculação normativa da razão do litígio.
	- Tipos de Decisão: Cadastro dos tipos e espécies de decisão que podem ser aplicadas nos casos em concreto, que serão usadas nas situações tipificadas como Decisão na parametrização do controle litigioso.
	- Lista de Serviços Outorgados: Cadastro manual ou po integração dos serviços outorgados pelo órgão que serão objeto do controle litigioso.
	- Mapeamento das Integrações: Cadastro e mapeamento dos webservices que se integram com o módulo para fazer, por exemplo, a automação da gestão e cobrança de multas com o sistema de arrecadação.

3. Para o funcionamento do módulo é necessária a parametrização em cada uma das telas acima.

4. Para parametrização dos dispositivos normativos, segue como exemplo a parametrização feita pela Anatel:
	https://goo.gl/B7cR1P

5. Para parametrização dos Tipos de Decisão, segue como exemplo a parametrização feita pela Anatel:
	https://goo.gl/5oZBLM

6. A parametrização da Lista de Serviços Outorgados e do Mapeamento das Integrações é DETERMINANTE para que o módulo possa fazer a gestão de multas e pagamentos relacionadas ao controle litigioso.