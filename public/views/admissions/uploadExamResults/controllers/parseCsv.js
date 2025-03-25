import { CSV } from "../../../js/modules/csv.mjs";

const COLUMNS_FORMAT = ["dni", "tipo examen", "calificacion"]

CSV.getCsvContent("csvFile", COLUMNS_FORMAT, "csv-upload" ,"loading", "csv-upload-s")