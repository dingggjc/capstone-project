import express from "express";
import puppeteer from "puppeteer";

const app = express();
const PORT = 3000;

// Route to generate PDF
app.get("/generate-pdf", async (req, res) => {
    try {
        console.log("Launching Puppeteer...");
        const browser = await puppeteer.launch();

        console.log("Opening new page...");
        const page = await browser.newPage();

        console.log(`Navigating to URL: ${url}`);
        await page.goto(url, { waitUntil: "networkidle0" });

        console.log("Generating PDF...");
        const pdfBuffer = await page.pdf({
            format: "A4",
            printBackground: true,
            margin: {
                top: "1in",
                bottom: "1in",
                left: "0.5in",
                right: "0.5in",
            },
        });

        console.log("Closing browser...");
        await browser.close();

        console.log("Sending PDF to client...");
        res.contentType("application/pdf");
        res.setHeader("Content-Disposition", "attachment; filename=report.pdf");
        res.send(pdfBuffer);
    } catch (error) {
        console.error("Error generating PDF:", error);
        res.status(500).send("An error occurred while generating the PDF.");
    }
});

app.use(express.static("public"));

app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
