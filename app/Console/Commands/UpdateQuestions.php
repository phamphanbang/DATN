<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateQuestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-questions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = 71;
        $end = 200;
        $questions = [
            "What type of business is being advertised?",
            "What will the listeners be able to do starting in April?",
            "Why does the speaker invite the listeners to visit a Web site?",
            "Why does the speaker thank the listeners?", "According to the speaker, what is scheduled for next month?", "What does the speaker imply when she says, \"it's a large space\"?", "According to the speaker, what is special about the restaurant?", "Who is Natasha?", "Why does the speaker say, \"I eat it all the time\"?", "Where is the announcement being made?", "What problem does the speaker mention?", "According to the speaker, why should the listeners talk with a staff member?", "Who is the speaker?", "What does the company sell?", "What does the speaker imply when she says, \"all I see are houses\"?", "What is the talk mainly about?", "Why did the company choose the product?", "What does the speaker say is offered with the product?", "What does the speaker say has recently been announced?", "According to the speaker, why do some people dislike a construction project?", "What will the speaker do next?", "What does the speaker thank the listeners for?", "In which division do the listeners most likely work?", "What does the speaker say he will provide?", "What event is being described?", "According to the speaker, what can the listeners find on a Web site?", "Look at the graphic. Which day is the event being held?", "What is the purpose of the call?", "Look at the graphic. Who is the speaker calling?", "What does the speaker ask the listener to do?", "Ms. Durkin asked for volunteers to help _____ with the employee fitness program.", "Lasner Electronics' staff have extensive _____ of current hardware systems.", "_____ a year, Tarrin Industrial Supply audits the accounts of all of its factories.", "Ms. Pham requested a refund _____ the coffeemaker she received was damaged.", "Information _____ the artwork in the lobby is available at the reception desk.", "With the Gema XTI binoculars, users can _____ see objects that are more than 100 meters away.", "The Physical Therapy Association is committed to keeping costs _____ for its certification programs.", "Mr. Brennel _____ positions in various areas of the company before he became president.", "To remain on schedule, editors must submit all _____ to the book to the authors by Friday.", "_____ industry professionals are allowed to purchase tickets to the Kuo Photography Fair.", "At Pharmbeck's banquet, Mr. Jones _____ a trophy for his performance in this year's quality-improvement initiative.", "Ms. Suto claims that important market trends become _____ with the use of data analysis.", "One of Grommer Consulting's goals is to enhance the relationship _____ salespeople and their customers.", "Depending on your answers to the survey, we _____ you to collect additional information.", "_____ Jemburger opened its newest franchise, the first 100 customers were given free hamburgers.", "Please include the serial number of your product in any _____ with the customer service department.", "The award-winning film Underwater Secrets promotes awareness _____ ocean pollution and its effects on our planet.", "BYF Company specializes in _____ promotional items to help companies advertise their brand.", "_____ the rent increase is less than 2 percent, Selwin Electrical Supply will continue to lease the space.", "Belden Hospital's chief of staff meets regularly with the staff to ensure that procedures _____ correctly.", "Any requests for time off should be addressed to the _____ department supervisor.", "World Fish Supply delivers the freshest fish possible thanks to innovative _____ and shipping methods.", "Company executives are currently reviewing the annual budget _____ submitted to them by the Financial Planning department.", "Even the CEO had to admit that Prasma Designs' win was _____ the result of fortunate timing.", "Mr. Singh took notes on _____ the focus group discussed during the morning session.", "Last year, Tadaka Computer Solutions ranked third _____ in regional earnings.", "_____ the popularity of the BPT39 wireless speaker, production will be increased fivefold starting next month.", "Zypo Properties has just signed a lease agreement with the law firm _____ offices are on the third floor.", "_____ events this year caused profits in the second and third quarters to differ significantly from original projections.", "The timeline for the pathway lighting project was extended to _____ input from the environmental commission.", "(131)", "(132)", "(133)", "(134)", "(135)", "(136)", "(137)", "(138)", "(139)", "(140)", "(141)", "(142)", "(143)", "(144)", "(145)", "(146)", "Where does Ms. Brown most likely work?", "What is Ziva asked to do?", "What is the purpose of the e-mail?", "According to the e-mail, what will managers do?", "What is mentioned about Carmont Media's employees?", "What is a purpose of Carmont Media's Star teams?", "What does Mr. Muro want Ms. Santos to do?", "At 9:36 A.M., what does Mr. Muro mean when he writes, \"I know\"?", "What is the purpose of the article?", "How did Mr. Chandler improve corporate security?", "In which of the positions marked [1], [2], [3], and [4] does the following sentence best belong? \"The training included 60 hours of instruction and a comprehensive written exam.\"", "Where would the card most likely be found?", "How often should the shaver be taken apart?", "What is indicated about the shaver's battery?", "What is indicated about the South American Art exhibit?", "Who is Mr. Carrera?", "According to the Web page, what can museum patrons do for an extra fee?", "Why did Mr. Koh start the online chat discussion?", "What does Mr. Koskinen recommend doing?", "At 10:19 A.M., what does Ms. Matova most likely mean when she writes, \"Sorry\"?", "What will Ms. Matova probably do with the package?", "Why was the e-mail sent?", "What is mentioned as a benefit of telecommuting?", "What is the company planning to do in the new year?", "In which of the positions marked [1], [2], [3], and [4] does the following sentence best belong? \"It should be noted that no decisions about telecommuting have been made.\"", "What is suggested about the first day of the Uppsala International Book Fair?", "Where will book fair attendees be able to participate in interactive activities?", "What is mentioned about the accompanying materials for the seminar?", "What are book fair attendees encouraged to do?", "In the Web page, what is indicated about Mountain and Forest's shipping?", "In the Web page, the word \"direct\" is closest in meaning to", "What is the purpose of the e-mail?", "How much did Ms. Shin pay for shipping?", "According to the e-mail, why might Ms. Shin decide to visit a local shop?", "What is indicated about Ms. Murakami?", "When will a specialist in business management be speaking?", "In the e-mail, the word \"slots\" is closest in meaning to", "What presentation will have to be canceled?", "According to the e-mail, what information is Ms. Murakami expecting to receive?", "What is Ms. Stawinski encouraged to do?", "How did some attendees get a free health checkup?", "What does Dr. Daumas most likely specialize in?", "What is indicated about the presentation on food?", "Who most likely distributed certificates?", "Who is the brochure intended for?", "What is indicated about SBS?", "What does Mr. Koshi mention about his lecturer?", "Where does Mr. Koshi's instructor work when she is not teaching?", "How will SBS be addressing Mr. Koshi's complaint?", "What is indicated about the line cook position?", "What is true about Mr. Hoang?", "Who is Mr. Overbeck?", "What is suggested about Boudreau Community College?", "Where does Ms. Riou most likely work?"
        ];
        foreach ($questions as $question) {
            DB::table('exam_questions')
                ->where('id', $start)
                ->update([
                    'question' => $question
                ]);
            $start++;
        }
    }
}
