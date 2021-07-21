import re
import os
import zipfile

def make_zipfile(output_filename, source_dir, excudedFiles):
    relroot = os.path.abspath(os.path.join(source_dir, os.pardir))
    with zipfile.ZipFile(output_filename, "w", zipfile.ZIP_DEFLATED) as zip:
        for root, dirs, files in os.walk(source_dir):
            for file in files:
                filename = os.path.join(root, file)
                if os.path.isfile(filename):
                    arcname = os.path.join(os.path.relpath(root, relroot), file)
                    if not re.search(excudedFiles, arcname):
                        zip.write(filename, arcname)

def main():
    path = "../../release"
    if not os.path.exists(path):
        os.mkdir(path)
    else:
        pass
    file = open("../../api/prepareExec.php", 'r')
    fileContent = file.read()
    file.close()
    regex = r"(?:[\s]*)(?:define\('API_VERSION', ')([0-9.]{3,})(?:'\);)"
    version = re.findall(regex, fileContent)[0]
    zipFileName = path + "/backend-" + version + ".zip"
    excudedFiles = r"(?:api\\.vscode|api\\logs|api\\settings.conf|api\\devConsole.php|api\\.gitignore|api\\composer.*)|(?:api\/.vscode|api\/logs|api\/settings.conf|api\/devConsole.php|api\/.gitignore|api\/composer.*)"
    make_zipfile(zipFileName, "../../api", excudedFiles)

if __name__ == "__main__":
    main()
