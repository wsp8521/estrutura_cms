-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 13-Jul-2017 às 22:33
-- Versão do servidor: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wtec_cms`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `wsp_category`
--

CREATE TABLE `wsp_category` (
  `id_cat` int(11) NOT NULL,
  `cat_title` varchar(45) NOT NULL,
  `cat_url` varchar(45) DEFAULT NULL,
  `cat_parent_Id` int(11) DEFAULT '0',
  `cat_desc` mediumtext COMMENT 'breve descrição da categoria',
  `cat_date` timestamp NULL DEFAULT NULL,
  `cat_type` varchar(45) DEFAULT NULL,
  `cat_include_menu` varchar(3) DEFAULT NULL,
  `cat_author` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `wsp_config`
--

CREATE TABLE `wsp_config` (
  `id_config` int(11) NOT NULL,
  `config_name` varchar(50) NOT NULL COMMENT 'nome do item de configuração',
  `config_value` varchar(100) DEFAULT NULL COMMENT 'valor da configuração'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `wsp_config`
--

INSERT INTO `wsp_config` (`id_config`, `config_name`, `config_value`) VALUES
(1, 'name_site', 'wtec informática'),
(2, 'template_site', 'default'),
(3, 'template_painel', 'default');

-- --------------------------------------------------------

--
-- Estrutura da tabela `wsp_menubar`
--

CREATE TABLE `wsp_menubar` (
  `id_menu` int(11) NOT NULL COMMENT 'tabela responsavel em recebe os menus de navegação',
  `id_page_menu` int(11) DEFAULT NULL COMMENT 'pega o id da pagina',
  `id_cat_menu` int(11) DEFAULT NULL COMMENT 'pega o id da categoria',
  `menu_type` varchar(45) DEFAULT NULL COMMENT 'define se o menu é de uma pagina ou de uma caegoria',
  `menu_name` varchar(45) NOT NULL,
  `menu_url` varchar(45) NOT NULL,
  `menu_status` varchar(3) DEFAULT NULL,
  `menu_order` int(11) DEFAULT NULL COMMENT 'ordem do menu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `wsp_menubar_painel`
--

CREATE TABLE `wsp_menubar_painel` (
  `id_menu` int(11) NOT NULL,
  `menu_file` varchar(30) NOT NULL COMMENT 'recebe o nome do arquvio php sem a extenção',
  `menu_label` varchar(30) DEFAULT NULL COMMENT 'rotulo do menu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tabela responsavel em armazenar os menus que serão utilizados no painel do sistema';

--
-- Extraindo dados da tabela `wsp_menubar_painel`
--

INSERT INTO `wsp_menubar_painel` (`id_menu`, `menu_file`, `menu_label`) VALUES
(1, 'page', 'Páginas'),
(2, 'cat', 'Categorias'),
(3, 'post', 'Posts'),
(4, 'user', 'Usuários'),
(5, 'config', 'Configurações');

-- --------------------------------------------------------

--
-- Estrutura da tabela `wsp_mod`
--

CREATE TABLE `wsp_mod` (
  `id_mod` int(11) NOT NULL,
  `mod_name` varchar(45) NOT NULL,
  `mod_status` int(11) DEFAULT NULL,
  `mod_user` int(11) DEFAULT NULL COMMENT 'tipo de usuário que poderão utilizar o modulo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `wsp_mod`
--

INSERT INTO `wsp_mod` (`id_mod`, `mod_name`, `mod_status`, `mod_user`) VALUES
(1, 'gallery', 1, 1),
(2, 'comment', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `wsp_page`
--

CREATE TABLE `wsp_page` (
  `id_page` int(11) NOT NULL,
  `page_title` varchar(50) NOT NULL,
  `page_url` varchar(50) NOT NULL,
  `page_content` longtext,
  `page_author` int(11) NOT NULL COMMENT 'autor da pagine',
  `page_incude_menu` varchar(3) DEFAULT NULL COMMENT 'define se a pagina será incluida no menu. valores: off- não/ on - sim',
  `page_status` varchar(3) DEFAULT NULL COMMENT 'define se a pagina será incluida no menu. valores: off- não/ on - sim',
  `page_data_create` timestamp NULL DEFAULT NULL,
  `page_date_update` timestamp NULL DEFAULT NULL,
  `page_type` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `wsp_permission_button`
--

CREATE TABLE `wsp_permission_button` (
  `id_perm` int(11) NOT NULL,
  `niver_user` int(11) DEFAULT NULL COMMENT 'nivel do usuáiro. 1 - assistente / 2 - editor / 3 - administrador',
  `button_action` varchar(45) DEFAULT NULL COMMENT 'botão de ação adicionar, editar, excluir, publicar',
  `value_buttom` int(11) DEFAULT NULL COMMENT 'valor do botão, 0 - invisivel / 1 - visivel'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tabela esponsavel e definir as permissões de butões de acordo com o usuário';

--
-- Extraindo dados da tabela `wsp_permission_button`
--

INSERT INTO `wsp_permission_button` (`id_perm`, `niver_user`, `button_action`, `value_buttom`) VALUES
(1, 1, 'adicionar', 1),
(2, 1, 'editar', 1),
(3, 1, 'excluir', 1),
(4, 1, 'publicar', 1),
(5, 2, 'adicionar', 1),
(6, 2, 'editar', 1),
(7, 2, 'excluir', 1),
(8, 2, 'publicar', 1),
(13, 3, 'publicar', 1),
(14, 3, 'adicionar', 1),
(15, 3, 'excluir', 1),
(16, 3, 'editar', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `wsp_permission_page`
--

CREATE TABLE `wsp_permission_page` (
  `id_per` int(11) NOT NULL,
  `user_nivel` int(11) DEFAULT NULL,
  `per_id_page` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `wsp_permission_page`
--

INSERT INTO `wsp_permission_page` (`id_per`, `user_nivel`, `per_id_page`) VALUES
(1, 1, 1),
(2, 1, 3),
(3, 1, 4),
(4, 2, 1),
(5, 2, 2),
(6, 2, 3),
(7, 2, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `wsp_posts`
--

CREATE TABLE `wsp_posts` (
  `id_post` int(11) NOT NULL,
  `post_cat_id` int(11) NOT NULL COMMENT 'categoria do post',
  `post_cat_parent` int(11) DEFAULT NULL COMMENT 'categoria pai do post',
  `post_title` varchar(50) NOT NULL,
  `post_url` varchar(50) NOT NULL,
  `post_content` longtext,
  `post_capa` varchar(45) DEFAULT NULL COMMENT 'imagem de capa',
  `post_date` timestamp NULL DEFAULT NULL COMMENT 'data de criação do post',
  `post_date_update` timestamp NULL DEFAULT NULL COMMENT 'data de atualização do post',
  `post_author` int(11) NOT NULL,
  `post_views` decimal(10,0) DEFAULT NULL COMMENT 'quantiade de visualizações do post',
  `post_last_views` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `post_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `wsp_post_gallery`
--

CREATE TABLE `wsp_post_gallery` (
  `id_gallery` int(11) NOT NULL,
  `post_gallery_id` int(11) DEFAULT NULL COMMENT 'post da galeria',
  `gallery_image` varchar(50) DEFAULT NULL,
  `gallery_thumb` varchar(50) DEFAULT NULL,
  `gallery_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `wsp_user`
--

CREATE TABLE `wsp_user` (
  `id_user` int(11) NOT NULL,
  `user_name` varchar(45) DEFAULT NULL,
  `user_last_name` varchar(45) DEFAULT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_pass` varchar(45) DEFAULT NULL,
  `user_status` varchar(3) DEFAULT NULL,
  `user_nivel` int(11) NOT NULL COMMENT '1 - administrador/ 2 - Editor / 3 - assistente',
  `user_avatar'` varchar(200) DEFAULT NULL,
  `user_date_create` timestamp NULL DEFAULT NULL COMMENT 'data da criação'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `wsp_user`
--

INSERT INTO `wsp_user` (`id_user`, `user_name`, `user_last_name`, `user_email`, `user_pass`, `user_status`, `user_nivel`, `user_avatar'`, `user_date_create`) VALUES
(1, 'wesley', 'santos pereira', 'wsp8521@hotmail.com', '202cb962ac59075b964b07152d234b70', '', 3, NULL, NULL),
(2, 'Pedro', 'Wayzer', 'pedro@hotmail.com', '202cb962ac59075b964b07152d234b70', NULL, 2, NULL, NULL),
(3, 'Ioneide', 'Fernandes', 'neide@hotmail.com', '202cb962ac59075b964b07152d234b70', NULL, 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wsp_category`
--
ALTER TABLE `wsp_category`
  ADD PRIMARY KEY (`id_cat`);

--
-- Indexes for table `wsp_config`
--
ALTER TABLE `wsp_config`
  ADD PRIMARY KEY (`id_config`);

--
-- Indexes for table `wsp_menubar`
--
ALTER TABLE `wsp_menubar`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `id_page_menu_idx` (`id_page_menu`),
  ADD KEY `id_cat_menu_idx` (`id_cat_menu`);

--
-- Indexes for table `wsp_menubar_painel`
--
ALTER TABLE `wsp_menubar_painel`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `wsp_mod`
--
ALTER TABLE `wsp_mod`
  ADD PRIMARY KEY (`id_mod`);

--
-- Indexes for table `wsp_page`
--
ALTER TABLE `wsp_page`
  ADD PRIMARY KEY (`id_page`);

--
-- Indexes for table `wsp_permission_button`
--
ALTER TABLE `wsp_permission_button`
  ADD PRIMARY KEY (`id_perm`);

--
-- Indexes for table `wsp_permission_page`
--
ALTER TABLE `wsp_permission_page`
  ADD PRIMARY KEY (`id_per`);

--
-- Indexes for table `wsp_posts`
--
ALTER TABLE `wsp_posts`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `id_cat_idx` (`post_cat_id`);

--
-- Indexes for table `wsp_post_gallery`
--
ALTER TABLE `wsp_post_gallery`
  ADD PRIMARY KEY (`id_gallery`),
  ADD KEY `id_post_idx` (`post_gallery_id`);

--
-- Indexes for table `wsp_user`
--
ALTER TABLE `wsp_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wsp_category`
--
ALTER TABLE `wsp_category`
  MODIFY `id_cat` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wsp_config`
--
ALTER TABLE `wsp_config`
  MODIFY `id_config` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `wsp_menubar`
--
ALTER TABLE `wsp_menubar`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT COMMENT 'tabela responsavel em recebe os menus de navegação';
--
-- AUTO_INCREMENT for table `wsp_menubar_painel`
--
ALTER TABLE `wsp_menubar_painel`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `wsp_mod`
--
ALTER TABLE `wsp_mod`
  MODIFY `id_mod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `wsp_page`
--
ALTER TABLE `wsp_page`
  MODIFY `id_page` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wsp_permission_button`
--
ALTER TABLE `wsp_permission_button`
  MODIFY `id_perm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `wsp_permission_page`
--
ALTER TABLE `wsp_permission_page`
  MODIFY `id_per` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `wsp_posts`
--
ALTER TABLE `wsp_posts`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wsp_post_gallery`
--
ALTER TABLE `wsp_post_gallery`
  MODIFY `id_gallery` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wsp_user`
--
ALTER TABLE `wsp_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `wsp_menubar`
--
ALTER TABLE `wsp_menubar`
  ADD CONSTRAINT `id_cat_menu` FOREIGN KEY (`id_cat_menu`) REFERENCES `wsp_category` (`id_cat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_page_menu` FOREIGN KEY (`id_page_menu`) REFERENCES `wsp_page` (`id_page`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `wsp_posts`
--
ALTER TABLE `wsp_posts`
  ADD CONSTRAINT `id_cat_post` FOREIGN KEY (`post_cat_id`) REFERENCES `wsp_category` (`id_cat`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `wsp_post_gallery`
--
ALTER TABLE `wsp_post_gallery`
  ADD CONSTRAINT `id_post` FOREIGN KEY (`post_gallery_id`) REFERENCES `wsp_posts` (`id_post`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
