#!/usr/bin/env bash
VERSION="0.1.5"
SVNDIR=/Users/yousan/svn/fb-share-count
GITDIR=/Users/yousan/git/fb-share-count
FILES=("fb-share-count.php" "languages" "readme.txt" "includes")

cd $SVNDIR
svn update

for (( I = 0; I < ${#FILES[@]}; ++I ))
do
  cp -R ${GITDIR}/${FILES[$I]} ${SVNDIR}/trunk
done

svn commit -m 'update plugin'

cd $SVNDIR
svn rm tags/${VERSION}
svn cp trunk tags/${VERSION}
svn ci -m "tagging ${VERSION}"

exit 0
