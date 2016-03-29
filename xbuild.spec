Name            : xBuild
Summary         : Build and deploy tools for PoiXson projects
Version         : 0.1
Release         : 1
BuildArch       : noarch
Provides        : xBuild
Requires        : php >= 5.6
Requires        : shellscripts >= 1.4.3
Requires        : bash, wget, screen, zip, unzip, grep, tree, dos2unix
Requires        : usr/sbin/useradd, /usr/bin/getent
#Requires        : gradle, maven2, composer, rpm-build
Prefix          : %{_bindir}/%{name}
%define _rpmfilename  %%{NAME}-%%{VERSION}-%%{RELEASE}.%%{ARCH}.rpm

Group           : Development Tools
License         : GPL-3.0
Packager        : PoiXson <support@poixson.com>
URL             : http://poixson.com/

%description
Build and deploy tools for PoiXson projects.



# avoid centos 5/6 extras processes on contents (especially brp-java-repack-jars)
%define __os_install_post %{nil}



### Prep ###
%prep



### Build ###
%build



### Install ###
%install
echo
echo "Install.."
# delete existing rpm's
%{__rm} -fv "%{_rpmdir}/%{name}-"*.noarch.rpm
# create directories
%{__install} -d -m 0755 \
	"${RPM_BUILD_ROOT}%{prefix}/" \
		|| exit 1
%{__install} -d -m 0755 \
	"${RPM_BUILD_ROOT}%{_sysconfdir}/%{name}/" \
		|| exit 1
# copy script files
for file in \
	xbuild.sh                   \
	xbuild.php                  \
	Builder.php                 \
	UsingDefaultGoalsEnum.php   \
	configs/config_abstract.php \
	configs/config_global.php   \
	configs/config_goals.php    \
	configs/config_xbuild.php   \
	configs/config_xdeploy.php  \
	goals/Goal.php              \
	goals/GoalShell.php         \
	goals/goal_box.php          \
	goals/goal_clean.php        \
	goals/goal_composer.php     \
	goals/goal_deploy.php       \
	goals/goal_git.php          \
	goals/goal_gradle.php       \
	goals/goal_group.php        \
	goals/goal_maven.php        \
	goals/goal_prep.php         \
	goals/goal_rpm.php          \
	goals/goal_shell.php        \
	goals/goal_version.php      \
; do
	%{__install} -m 0550 \
		"%{SOURCE_ROOT}/src/${file}" \
		"${RPM_BUILD_ROOT}%{prefix}/${file}" \
			|| exit 1
done
# configs
for file in \
	global.json                 \
; do
	%{__install} -m 0644 \
		"%{SOURCE_ROOT}/${file}" \
		"${RPM_BUILD_ROOT}%{_sysconfdir}/%{name}/${file}" \
			|| exit 1
done
# readme
%{__install} -d -m 0444 \
	"%{SOURCE_ROOT}/changelog.txt" \
	"${RPM_BUILD_ROOT}%{prefix}/changelog.txt" \
		|| exit 1



# alias symlinks
ln -sf  "%{prefix}/xbuild.sh"  "${RPM_BUILD_ROOT}%{_bindir}/xbuild"



%check



%clean
if [ ! -z "%{_topdir}" ]; then
        %{__rm} -rf --preserve-root "%{_topdir}" \
                || echo "Failed to delete build root!"
fi



%pre
/usr/bin/getent group  xbuild || /usr/sbin/groupadd -r xbuild
/usr/bin/getent passwd xbuild || /usr/sbin/useradd  -r -d "%{prefix}/" -s /sbin/nologin xbuild



### Files ###
%files
%defattr(-,xbuild,xbuild,-)
%{prefix}/xbuild.sh
%{prefix}/xbuild.php
%{prefix}/Builder.php
%{prefix}/UsingDefaultGoalsEnum.php
%{prefix}/configs/config_abstract.php
%{prefix}/configs/config_global.php
%{prefix}/configs/config_goals.php
%{prefix}/configs/config_xbuild.php
%{prefix}/configs/config_xdeploy.php
%{prefix}/goals/Goal.php
%{prefix}/goals/GoalShell.php
%{prefix}/goals/goal_box.php
%{prefix}/goals/goal_clean.php
%{prefix}/goals/goal_composer.php
%{prefix}/goals/goal_deploy.php
%{prefix}/goals/goal_git.php
%{prefix}/goals/goal_gradle.php
%{prefix}/goals/goal_group.php
%{prefix}/goals/goal_maven.php
%{prefix}/goals/goal_prep.php
%{prefix}/goals/goal_rpm.php
%{prefix}/goals/goal_shell.php
%{prefix}/goals/goal_version.php
%{_bindir}/xbuild
