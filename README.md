Public domain API to access LDS scriptures database in SQLite format. Written in PHP. See https://api.nephi.org/ for homepage.

To download LDS scriptures (King James Version of the Bible, Book of Mormon, Doctrine & Covenants, Pearl of Great Price) see https://scriptures.nephi.org/

The scriptures API accepts a string of books, chapters, and verses and returns a JSON object with the text.

To access the API, query "https://api.nephi.org/scriptures/?q=Genesis+1:1"

You can also POST a request.

The format of the text accepts these patterns:

Book Chapter:Verse[-Verse,Verses][, Chapter:Verse][; Book Chapter:Verse](repeated)

Separate books and verses must be separated by a semicolon ';'

You can use spaces or + sign to separate words.

All input is converted to lower-case.

Books, chapters and verses that don't exist will simply not be included, no errors or empty placeholders are shown.

"Short" versions of a book title are also accepted. For example: Proverbs and Prov.

Some examples:

- Genesis 1:1
- Exodus 2:15-16
- Leviticus 3:1-3,5
- Numbers 4:5,7,16, 5:1, 5:2
- Numbers 3:1-3,6-7, 8-10, 22,15, 4,11; Deuteronomy 1:3
- Josh. 6:1-3
- Matthew 24:35
- 2 Nephi 2:8
- Articles of Faith 1:1-13

Long titles:

Genesis, Exodus, Leviticus, Numbers, Deuteronomy, Joshua, Judges, Ruth, 1 Samuel, 2 Samuel, 1 Kings, 2 Kings, 1 Chronicles, 2 Chronicles, Ezra, Nehemiah, Esther, Job, Psalms, Proverbs, Ecclesiastes, Solomon's Song, Isaiah, Jeremiah, Lamentations, Ezekiel, Daniel, Hosea, Joel, Amos, Obadiah, Jonah, Micah, Nahum, Habakkuk, Zephaniah, Haggai, Zechariah, Malachi, Matthew, Mark, Luke, John, Acts, Romans, 1 Corinthians, 2 Corinthians, Galatians, Ephesians, Philippians, Colossians, 1 Thessalonians, 2 Thessalonians, 1 Timothy, 2 Timothy, Titus, Philemon, Hebrews, James, 1 Peter, 2 Peter, 1 John, 2 John, 3 John, Jude, Revelation, 1 Nephi, 2 Nephi, Jacob, Enos, Jarom, Omni, Words of Mormon, Mosiah, Alma, Helaman, 3 Nephi, 4 Nephi, Mormon, Ether, Moroni, Doctrine and Covenants, Moses, Abraham, Joseph Smith--Matthew, Joseph Smith--History, Articles of Faith

Short titles:

Gen., Ex., Lev., Num., Deut., Josh., Judg., Ruth, 1 Sam., 2 Sam., 1 Kgs., 2 Kgs., 1 Chr., 2 Chr., Ezra, Neh., Esth., Job, Ps., Prov., Eccl., Song., Isa., Jer., Lam., Ezek., Dan., Hosea, Joel, Amos, Obad., Jonah, Micah, Nahum, Hab., Zeph., Hag., Zech., Mal., Matt., Mark, Luke, John, Acts, Rom., 1 Cor., 2 Cor., Gal., Eph., Philip., Col., 1 Thes., 2 Thes., 1 Tim., 2 Tim., Titus, Philem., Heb., James, 1 Pet., 2 Pet., 1 Jn., 2 Jn., 3 Jn., Jude, Rev., 1 Ne., 2 Ne., Jacob, Enos, Jarom, Omni, W of M, Mosiah, Alma, Hel., 3 Ne., 4 Ne., Morm., Ether, Moro., D&C, Moses, Abr., Js-M, Js-H, A of F
